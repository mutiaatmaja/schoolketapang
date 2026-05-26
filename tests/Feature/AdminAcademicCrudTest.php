<?php

namespace Tests\Feature;

use App\Imports\StudentsImport;
use App\Imports\TeachersImport;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAcademicCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_classes_teachers_and_students(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.guru')
            ->call('openCreate')
            ->set('name', 'Ibu Rina Kartika')
            ->set('nuptk', '1234567890123456')
            ->set('nip', '198001012010012001')
            ->set('nik', '3173000000001111')
            ->set('gender', 'Perempuan')
            ->set('birthPlace', 'Ketapang')
            ->set('birthDate', '1980-01-01')
            ->set('employmentStatus', 'Tetap')
            ->set('religion', 'Islam')
            ->set('address', 'Jl. Pendidikan No. 1')
            ->set('phone', '081234567890')
            ->set('email', 'rina@example.com')
            ->call('save');

        $teacher = Teacher::query()->where('email', 'rina@example.com')->firstOrFail();

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.kelas')
            ->call('openCreate')
            ->set('name', '1')
            ->set('teacherId', $teacher->id)
            ->call('save');

        $schoolClass = SchoolClass::query()->where('name', '1')->firstOrFail();

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.siswa')
            ->call('openCreate')
            ->set('schoolClassId', $schoolClass->id)
            ->set('name', 'Ahmad Fauzi')
            ->set('nis', '2025001')
            ->set('nisn', '1234567890')
            ->set('birthPlace', 'Ketapang')
            ->set('birthDate', '2015-06-10')
            ->set('nik', '3173000000002222')
            ->set('gender', 'Laki-laki')
            ->set('religion', 'Islam')
            ->set('fatherName', 'Budi Santoso')
            ->set('motherName', 'Siti Rahma')
            ->set('fatherOccupation', 'Wiraswasta')
            ->set('motherOccupation', 'Ibu Rumah Tangga')
            ->set('fatherPhone', '081200000001')
            ->set('motherPhone', '081200000002')
            ->set('address', 'Jl. Pelajar No. 2')
            ->set('notes', 'Catatan awal')
            ->set('status', 'AKTIF')
            ->call('save');

        $student = Student::query()->where('nis', '2025001')->firstOrFail();

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.siswa')
            ->call('openEdit', $student->id)
            ->set('status', 'LULUS')
            ->call('save')
            ->call('confirmDelete', $student->id)
            ->call('delete');

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.kelas')
            ->call('confirmDelete', $schoolClass->id)
            ->call('delete');

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.guru')
            ->call('confirmDelete', $teacher->id)
            ->call('delete');

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
        $this->assertDatabaseMissing('school_classes', ['id' => $schoolClass->id]);
        $this->assertDatabaseMissing('teachers', ['id' => $teacher->id]);
    }

    public function test_admin_can_create_student_with_optional_identity_and_parent_fields_empty(): void
    {
        $user = User::factory()->create();
        $schoolClass = SchoolClass::factory()->create(['name' => '1']);

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.siswa')
            ->call('openCreate')
            ->set('schoolClassId', $schoolClass->id)
            ->set('name', 'Mira Safitri')
            ->set('nis', '2025999')
            ->set('birthPlace', 'Ketapang')
            ->set('birthDate', '2015-01-05')
            ->set('nik', '')
            ->set('gender', 'Perempuan')
            ->set('religion', 'Islam')
            ->set('fatherName', '')
            ->set('motherName', '')
            ->set('fatherOccupation', '')
            ->set('motherOccupation', '')
            ->set('fatherPhone', '')
            ->set('motherPhone', '')
            ->set('address', '')
            ->set('notes', '')
            ->set('status', 'AKTIF')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('students', [
            'nis' => '2025999',
            'nik' => null,
            'father_name' => null,
            'mother_name' => null,
            'father_phone' => null,
            'mother_phone' => null,
            'address' => null,
        ]);
    }

    public function test_teacher_import_creates_records(): void
    {
        $import = new TeachersImport;

        $import->collection(new Collection([
            new Collection([
                'nama' => 'Bapak Dedi Saputra',
                'nuptk' => '1234567890123457',
                'nip' => '198205052010011002',
                'nik' => '3173000000003333',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Pontianak',
                'tanggal_lahir' => '1982-05-05',
                'status_kepegawaian' => 'Tetap',
                'agama' => 'Islam',
                'alamat' => 'Jl. Guru No. 3',
                'hp' => '081300000003',
                'email' => 'dedi@example.com',
            ]),
        ]));

        $this->assertSame(1, $import->processedRows());
        $this->assertDatabaseHas('teachers', ['email' => 'dedi@example.com']);
    }

    public function test_teacher_import_from_uploaded_csv_file_creates_records(): void
    {
        $user = User::factory()->create();

        $csv = implode("\n", [
            'nama,nuptk,nip,nik,jenis_kelamin,tempat_lahir,tanggal_lahir,status_kepegawaian,agama,alamat,hp,email',
            'Bapak Dedi Saputra,1234567890123457,198205052010011002,3173000000003333,Laki-laki,Pontianak,1982-05-05,Tetap,Islam,"Jl. Guru No. 3",081300000003,dedi@example.com',
        ]);

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.guru')
            ->call('openImport')
            ->set('importFile', UploadedFile::fake()->createWithContent('guru.csv', $csv))
            ->call('importTeachers')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('teachers', ['email' => 'dedi@example.com']);
    }

    public function test_teacher_import_shows_error_when_uploaded_file_has_no_data_rows(): void
    {
        $user = User::factory()->create();

        $csv = 'nama,nuptk,nip,nik,jenis_kelamin,tempat_lahir,tanggal_lahir,status_kepegawaian,agama,alamat,hp,email';

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.guru')
            ->call('openImport')
            ->set('importFile', UploadedFile::fake()->createWithContent('guru-kosong.csv', $csv))
            ->call('importTeachers')
            ->assertHasErrors(['importFile'])
            ->assertDispatched('toast');
    }

    public function test_student_import_creates_records_and_resolves_class(): void
    {
        $import = new StudentsImport;

        $import->collection(new Collection([
            [
                'nama_siswa' => 'Nur Aisyah',
                'nis' => '2025002',
                'nisn' => '1234567891',
                'tempat_lahir' => 'Ketapang',
                'tanggal_lahir' => '2014-02-10',
                'nik' => '3173000000004444',
                'jenis_kelamin' => 'Perempuan',
                'agama' => 'Islam',
                'nama_ayah' => 'Ahmad Yani',
                'nama_ibu' => 'Siti Aisyah',
                'pekerjaan_ayah' => 'Petani',
                'pekerjaan_ibu' => 'Pedagang',
                'no_hp_ayah' => '081300000004',
                'no_hp_ibu' => '081300000005',
                'alamat' => 'Jl. Siswa No. 4',
                'keterangan' => 'Aktif',
                'kelas' => '2',
                'status' => 'AKTIF',
            ],
        ]));

        $this->assertSame(1, $import->processedRows());
        $this->assertDatabaseHas('school_classes', ['name' => '2']);
        $this->assertDatabaseHas('students', ['nis' => '2025002']);
    }

    public function test_student_import_from_uploaded_csv_file_creates_records(): void
    {
        $user = User::factory()->create();

        $csv = implode("\n", [
            'nama_siswa,nis,nisn,tempat_lahir,tanggal_lahir,nik,jenis_kelamin,agama,nama_ayah,nama_ibu,pekerjaan_ayah,pekerjaan_ibu,no_hp_ayah,no_hp_ibu,alamat,keterangan,kelas,status',
            'Nur Aisyah,2025002,1234567891,Ketapang,2014-02-10,3173000000004444,Perempuan,Islam,Ahmad Yani,Siti Aisyah,Petani,Pedagang,081300000004,081300000005,"Jl. Siswa No. 4",Aktif,2,AKTIF',
        ]);

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.siswa')
            ->call('openImport')
            ->set('importFile', UploadedFile::fake()->createWithContent('siswa.csv', $csv))
            ->call('importStudents')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('school_classes', ['name' => '2']);
        $this->assertDatabaseHas('students', ['nis' => '2025002']);
    }

    public function test_student_import_shows_error_when_uploaded_file_has_no_data_rows(): void
    {
        $user = User::factory()->create();

        $csv = 'nama_siswa,nis,nisn,tempat_lahir,tanggal_lahir,nik,jenis_kelamin,agama,nama_ayah,nama_ibu,pekerjaan_ayah,pekerjaan_ibu,no_hp_ayah,no_hp_ibu,alamat,keterangan,kelas,status';

        Livewire::actingAs($user)
            ->test('pages::admin.akademik.siswa')
            ->call('openImport')
            ->set('importFile', UploadedFile::fake()->createWithContent('siswa-kosong.csv', $csv))
            ->call('importStudents')
            ->assertHasErrors(['importFile'])
            ->assertDispatched('toast');
    }

    public function test_academic_summary_page_uses_dynamic_counts(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        Role::query()->create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Mengelola data operasional sekolah dan PPDB.',
        ]);
        $user->syncRoles(['admin']);

        Teacher::factory()->count(2)->create();
        $class = SchoolClass::factory()->create(['name' => '3']);
        Student::factory()->count(3)->create(['school_class_id' => $class->id]);

        $response = $this->actingAs($user)->get(route('admin.akademik.index'));

        $response->assertStatus(200);
        $response->assertSee('3', false);
    }
}
