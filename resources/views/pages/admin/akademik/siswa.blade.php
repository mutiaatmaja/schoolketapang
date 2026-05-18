<?php

use App\Exports\StudentsTemplateExport;
use App\Imports\StudentsImport;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

new class extends Component {
    use WithFileUploads;

    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public bool $showImportModal = false;

    public ?int $schoolClassId = null;

    public string $name = '';

    public string $nis = '';

    public string $nisn = '';

    public string $birthPlace = '';

    public string $birthDate = '';

    public string $nik = '';

    public string $gender = 'Laki-laki';

    public string $religion = 'Islam';

    public string $fatherName = '';

    public string $motherName = '';

    public string $fatherOccupation = '';

    public string $motherOccupation = '';

    public string $fatherPhone = '';

    public string $motherPhone = '';

    public string $address = '';

    public string $notes = '';

    public string $status = 'AKTIF';

    public $importFile = null;

    public function rules(): array
    {
        return [
            'schoolClassId' => ['required', 'integer', 'exists:school_classes,id'],
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:30', Rule::unique('students', 'nis')->ignore($this->editingId)],
            'nisn' => ['nullable', 'string', 'max:30', Rule::unique('students', 'nisn')->ignore($this->editingId)],
            'birthPlace' => ['required', 'string', 'max:100'],
            'birthDate' => ['required', 'date'],
            'nik' => ['required', 'string', 'max:30', Rule::unique('students', 'nik')->ignore($this->editingId)],
            'gender' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'religion' => ['required', 'string', 'max:50'],
            'fatherName' => ['required', 'string', 'max:255'],
            'motherName' => ['required', 'string', 'max:255'],
            'fatherOccupation' => ['nullable', 'string', 'max:255'],
            'motherOccupation' => ['nullable', 'string', 'max:255'],
            'fatherPhone' => ['nullable', 'string', 'max:30'],
            'motherPhone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:AKTIF,LULUS,KELUAR'],
        ];
    }

    public function getStudentsProperty(): Collection
    {
        return Student::query()
            ->with('schoolClass:id,name')
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery
                        ->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nis', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%')
                        ->orWhereHas('schoolClass', function ($classQuery): void {
                            $classQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->ordered()
            ->get();
    }

    public function getClassesProperty(): Collection
    {
        return SchoolClass::query()->ordered()->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $student = Student::query()->findOrFail($id);

        $this->editingId = $student->id;
        $this->schoolClassId = $student->school_class_id;
        $this->name = $student->name;
        $this->nis = $student->nis;
        $this->nisn = $student->nisn ?? '';
        $this->birthPlace = $student->birth_place;
        $this->birthDate = $student->birth_date?->format('Y-m-d') ?? '';
        $this->nik = $student->nik;
        $this->gender = $student->gender;
        $this->religion = $student->religion;
        $this->fatherName = $student->father_name;
        $this->motherName = $student->mother_name;
        $this->fatherOccupation = $student->father_occupation ?? '';
        $this->motherOccupation = $student->mother_occupation ?? '';
        $this->fatherPhone = $student->father_phone ?? '';
        $this->motherPhone = $student->mother_phone ?? '';
        $this->address = $student->address ?? '';
        $this->notes = $student->notes ?? '';
        $this->status = $student->status;
        $this->showFormModal = true;
    }

    public function openImport(): void
    {
        $this->resetImportForm();
        $this->showImportModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        Student::query()->updateOrCreate(
            ['id' => $this->editingId],
            [
                'school_class_id' => $validated['schoolClassId'],
                'name' => $validated['name'],
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?: null,
                'birth_place' => $validated['birthPlace'],
                'birth_date' => $validated['birthDate'],
                'nik' => $validated['nik'],
                'gender' => $validated['gender'],
                'religion' => $validated['religion'],
                'father_name' => $validated['fatherName'],
                'mother_name' => $validated['motherName'],
                'father_occupation' => $validated['fatherOccupation'] ?: null,
                'mother_occupation' => $validated['motherOccupation'] ?: null,
                'father_phone' => $validated['fatherPhone'] ?: null,
                'mother_phone' => $validated['motherPhone'] ?: null,
                'address' => $validated['address'] ?: null,
                'notes' => $validated['notes'] ?: null,
                'status' => $validated['status'],
            ],
        );

        $message = $this->editingId ? 'Data siswa berhasil diperbarui.' : 'Data siswa berhasil ditambahkan.';

        $this->closeModal();
        $this->dispatch('toast', type: 'success', message: $message);
    }

    public function importStudents(): void
    {
        $this->validate([
            'importFile' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $import = new StudentsImport();
        Excel::import($import, $this->importFile);

        $this->resetImportForm();
        $this->showImportModal = false;
        $this->dispatch('toast', type: 'success', message: 'Import siswa berhasil. ' . $import->processedRows() . ' baris diproses.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new StudentsTemplateExport(), 'template-import-siswa.xlsx');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        Student::query()->findOrFail($this->deletingId)->delete();

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('toast', type: 'success', message: 'Data siswa berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->resetForm();
        $this->resetImportForm();
        $this->showFormModal = false;
        $this->showDeleteModal = false;
        $this->showImportModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->schoolClassId = null;
        $this->name = '';
        $this->nis = '';
        $this->nisn = '';
        $this->birthPlace = '';
        $this->birthDate = '';
        $this->nik = '';
        $this->gender = 'Laki-laki';
        $this->religion = 'Islam';
        $this->fatherName = '';
        $this->motherName = '';
        $this->fatherOccupation = '';
        $this->motherOccupation = '';
        $this->fatherPhone = '';
        $this->motherPhone = '';
        $this->address = '';
        $this->notes = '';
        $this->status = 'AKTIF';
    }

    private function resetImportForm(): void
    {
        $this->resetValidation();
        $this->importFile = null;
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Akademik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Data Siswa</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola data induk siswa lengkap dan dukung import massal dari Excel.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Siswa</h2>
                <p class="mt-1 text-sm text-slate-500">Data siswa mengacu ke kelas yang sudah dibuat pada modul kelas.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="button" wire:click="openImport" wire:loading.attr="disabled" wire:target="openImport"
                    class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                    <span wire:loading.remove wire:target="openImport">Import Excel</span>
                    <span wire:loading wire:target="openImport">Membuka...</span>
                </button>
                <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                    class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                    <span wire:loading.remove wire:target="openCreate">Tambah Siswa</span>
                    <span wire:loading wire:target="openCreate">Membuka...</span>
                </button>
            </div>
        </div>

        <div class="relative mt-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, NIS, NIK, status, atau kelas"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-24 text-sm" />
            <span wire:loading wire:target="search" class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Mencari...</span>
        </div>

        <div class="relative mt-4 overflow-hidden rounded-2xl border border-slate-200">
            <div wire:loading.flex wire:target="search,save,delete,openEdit,confirmDelete,importStudents"
                class="absolute inset-0 z-10 hidden items-center justify-center bg-white/80 text-sm font-semibold text-slate-600">
                Memproses data siswa...
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1024px] text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Identitas</th>
                            <th class="px-4 py-3 font-medium">Kelas</th>
                            <th class="px-4 py-3 font-medium">Orang Tua</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->students as $student)
                            <tr wire:key="student-{{ $student->id }}" class="border-t border-slate-100 align-top">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-800">{{ $student->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">NIS {{ $student->nis }} · NIK {{ $student->nik }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">Kelas {{ $student->schoolClass?->name }}</td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p>{{ $student->father_name }}</p>
                                    <p class="mt-1">{{ $student->mother_name }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $student->status }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="openEdit({{ $student->id }})" wire:loading.attr="disabled" wire:target="openEdit({{ $student->id }})"
                                            class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700 disabled:opacity-50">
                                            <span wire:loading.remove wire:target="openEdit({{ $student->id }})">Edit</span>
                                            <span wire:loading wire:target="openEdit({{ $student->id }})">Membuka...</span>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $student->id }})" wire:loading.attr="disabled" wire:target="confirmDelete({{ $student->id }})"
                                            class="rounded-lg bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 disabled:opacity-50">
                                            <span wire:loading.remove wire:target="confirmDelete({{ $student->id }})">Hapus</span>
                                            <span wire:loading wire:target="confirmDelete({{ $student->id }})">Menyiapkan...</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @if ($showFormModal)
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:py-10" style="z-index: 120;">
            <div class="w-full max-w-5xl max-h-[calc(100vh-3rem)] overflow-y-auto rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">{{ $editingId ? 'Edit Siswa' : 'Tambah Siswa' }}</h2>
                        <p class="mt-1 text-sm text-slate-500">Lengkapi identitas siswa, relasi kelas, dan data orang tua/wali.</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-5">
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nama Siswa</label>
                            <input type="text" wire:model="name" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Kelas</label>
                            <select wire:model="schoolClassId" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                                <option value="">Pilih kelas</option>
                                @foreach ($this->classes as $class)
                                    <option value="{{ $class->id }}">Kelas {{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('schoolClassId') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Status</label>
                            <select wire:model="status" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                                <option value="AKTIF">AKTIF</option>
                                <option value="LULUS">LULUS</option>
                                <option value="KELUAR">KELUAR</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">NIS</label>
                            <input type="text" wire:model="nis" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('nis') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">NISN</label>
                            <input type="text" wire:model="nisn" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('nisn') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">NIK</label>
                            <input type="text" wire:model="nik" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('nik') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Jenis Kelamin</label>
                            <select wire:model="gender" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('gender') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Agama</label>
                            <input type="text" wire:model="religion" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('religion') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Tempat Lahir</label>
                            <input type="text" wire:model="birthPlace" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('birthPlace') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Tanggal Lahir</label>
                            <input type="date" wire:model="birthDate" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('birthDate') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nama Ayah</label>
                            <input type="text" wire:model="fatherName" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('fatherName') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nama Ibu</label>
                            <input type="text" wire:model="motherName" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('motherName') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Pekerjaan Ayah</label>
                            <input type="text" wire:model="fatherOccupation" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('fatherOccupation') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Pekerjaan Ibu</label>
                            <input type="text" wire:model="motherOccupation" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('motherOccupation') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">No. HP Ayah</label>
                            <input type="text" wire:model="fatherPhone" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('fatherPhone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">No. HP Ibu</label>
                            <input type="text" wire:model="motherPhone" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('motherPhone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="xl:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Alamat</label>
                            <textarea wire:model="address" rows="4" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"></textarea>
                            @error('address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="xl:col-span-3">
                            <label class="text-sm font-semibold text-slate-700">Keterangan</label>
                            <textarea wire:model="notes" rows="4" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"></textarea>
                            @error('notes') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">Simpan</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showImportModal)
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:items-center sm:py-10" style="z-index: 120;">
            <div class="w-full max-w-xl rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Import Siswa</h2>
                        <p class="mt-1 text-sm text-slate-500">Gunakan template agar nama kolom dan format tanggal sesuai.</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <div class="mt-5 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-600">
                    Pastikan kolom <span class="font-semibold text-slate-800">kelas</span> berisi angka 1 sampai 6 dan status menggunakan AKTIF, LULUS, atau KELUAR.
                </div>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <button type="button" wire:click="downloadTemplate" wire:loading.attr="disabled" wire:target="downloadTemplate"
                        class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                        <span wire:loading.remove wire:target="downloadTemplate">Unduh Template</span>
                        <span wire:loading wire:target="downloadTemplate">Menyiapkan...</span>
                    </button>
                </div>

                <form wire:submit="importStudents" class="mt-5 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">File Excel</label>
                        <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv" class="mt-2 block w-full text-sm text-slate-600" />
                        <p wire:loading wire:target="importFile" class="mt-1 text-xs text-slate-500">Mengunggah file...</p>
                        @error('importFile') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="importStudents" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                            <span wire:loading.remove wire:target="importStudents">Import</span>
                            <span wire:loading wire:target="importStudents">Mengimpor...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:items-center sm:py-10" style="z-index: 120;">
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-bold text-slate-800">Hapus Siswa</h2>
                <p class="mt-2 text-sm text-slate-600">Data siswa yang dihapus tidak bisa dikembalikan.</p>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Batal</button>
                    <button type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                        <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                        <span wire:loading wire:target="delete">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
