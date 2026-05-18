<?php

use App\Models\SpmbRegistration;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::ppdb.app')] class extends Component {
    use WithFileUploads;

    public int $step = 1;

    public bool $submitted = false;

    public ?string $registrationNumber = null;

    public string $name = '';

    public string $birthPlace = '';

    public string $birthDate = '';

    public string $nik = '';

    public string $familyCardNumber = '';

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

    public $birthCertificate = null;

    public $familyCard = null;

    public $studentPhoto = null;

    public $kindergartenCertificate = null;

    public array $stepLabels = [
        1 => 'Cek NIK',
        2 => 'Data Siswa',
        3 => 'Orang Tua',
        4 => 'Berkas',
        5 => 'Periksa',
    ];

    public function nextStep(): void
    {
        $this->validate($this->rulesForStep($this->step));

        if ($this->step < 5) {
            $this->step++;
        }
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function submitForm(): void
    {
        $validated = $this->validate($this->rules());

        $registrationNumber = $this->generateRegistrationNumber();

        SpmbRegistration::query()->create([
            'registration_number' => $registrationNumber,
            'name' => $validated['name'],
            'birth_place' => $validated['birthPlace'],
            'birth_date' => $validated['birthDate'],
            'nik' => $validated['nik'],
            'family_card_number' => $validated['familyCardNumber'],
            'gender' => $validated['gender'],
            'religion' => $validated['religion'],
            'father_name' => $validated['fatherName'],
            'mother_name' => $validated['motherName'],
            'father_occupation' => $validated['fatherOccupation'] ?: null,
            'mother_occupation' => $validated['motherOccupation'] ?: null,
            'father_phone' => $validated['fatherPhone'],
            'mother_phone' => $validated['motherPhone'] ?: null,
            'address' => $validated['address'],
            'notes' => $validated['notes'] ?: null,
            'birth_certificate_path' => $this->storeDocument(
                file: $this->birthCertificate,
                directory: 'akte_lahir',
                registrationNumber: $registrationNumber,
                includeRegistrationNumber: true,
            ),
            'family_card_path' => $this->storeDocument(
                file: $this->familyCard,
                directory: 'kk',
                registrationNumber: $registrationNumber,
                includeRegistrationNumber: false,
            ),
            'student_photo_path' => $this->storeDocument(
                file: $this->studentPhoto,
                directory: 'pasfoto',
                registrationNumber: $registrationNumber,
                includeRegistrationNumber: true,
            ),
            'kindergarten_certificate_path' => $this->kindergartenCertificate ? $this->storeDocument(
                file: $this->kindergartenCertificate,
                directory: 'ijazah_tk',
                registrationNumber: $registrationNumber,
                includeRegistrationNumber: true,
            ) : null,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $this->registrationNumber = $registrationNumber;
        $this->submitted = true;
        $this->dispatch('toast', type: 'success', message: 'Pendaftaran berhasil dikirim. Simpan nomor pendaftaran Anda.');
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'digits:16', Rule::unique('spmb_registrations', 'nik')],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'birthPlace' => ['required', 'string', 'max:100'],
            'birthDate' => ['required', 'date', 'before_or_equal:today'],
            'familyCardNumber' => ['required', 'digits:16'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'religion' => ['required', 'string', 'max:50'],
            'fatherName' => ['required', 'string', 'min:3', 'max:255'],
            'motherName' => ['required', 'string', 'min:3', 'max:255'],
            'fatherOccupation' => ['nullable', 'string', 'max:255'],
            'motherOccupation' => ['nullable', 'string', 'max:255'],
            'fatherPhone' => ['required', 'string', 'max:30'],
            'motherPhone' => ['nullable', 'string', 'max:30'],
            'address' => ['required', 'string', 'min:10'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'birthCertificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072'],
            'familyCard' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072'],
            'studentPhoto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'kindergartenCertificate' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.unique' => 'NIK sudah terdaftar. Jika ini kekeliruan, silakan hubungi operator sekolah.',
        ];
    }

    protected function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'nik' => ['required', 'digits:16', Rule::unique('spmb_registrations', 'nik')],
            ],
            2 => [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'birthPlace' => ['required', 'string', 'max:100'],
                'birthDate' => ['required', 'date', 'before_or_equal:today'],
                'familyCardNumber' => ['required', 'digits:16'],
                'gender' => ['required', 'in:Laki-laki,Perempuan'],
                'religion' => ['required', 'string', 'max:50'],
            ],
            3 => [
                'fatherName' => ['required', 'string', 'min:3', 'max:255'],
                'motherName' => ['required', 'string', 'min:3', 'max:255'],
                'fatherOccupation' => ['nullable', 'string', 'max:255'],
                'motherOccupation' => ['nullable', 'string', 'max:255'],
                'fatherPhone' => ['required', 'string', 'max:30'],
                'motherPhone' => ['nullable', 'string', 'max:30'],
                'address' => ['required', 'string', 'min:10'],
                'notes' => ['nullable', 'string', 'max:1000'],
            ],
            4 => [
                'birthCertificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072'],
                'familyCard' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072'],
                'studentPhoto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
                'kindergartenCertificate' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072'],
            ],
            default => [],
        };
    }

    public function selectedFileLabel(mixed $file): ?string
    {
        if ($file === null) {
            return null;
        }

        return method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : null;
    }

    private function storeDocument(
        TemporaryUploadedFile $file,
        string $directory,
        string $registrationNumber,
        bool $includeRegistrationNumber,
    ): string
    {
        $nameSegment = Str::of($this->name)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->value();

        $segments = array_filter([
            $includeRegistrationNumber ? $registrationNumber : null,
            $this->nik,
            $nameSegment,
        ]);

        $filename = implode('_', $segments).'.'.$file->getClientOriginalExtension();

        return $file->storeAs($directory, $filename, 'public');
    }

    private function generateRegistrationNumber(): string
    {
        do {
            $number = 'SPMB-' . now()->format('Y') . '-' . Str::upper(Str::random(6));
        } while (SpmbRegistration::query()->where('registration_number', $number)->exists());

        return $number;
    }
};
?>

@php
    $progressWidth = ($step / count($stepLabels)) * 100;
@endphp

<div class="space-y-5">
    <header class="rounded-[28px] bg-white px-5 py-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#1d4f45]">Form Pendaftaran SPMB</p>
        <h1 class="mt-2 text-2xl font-bold leading-tight text-slate-900">Isi formulir secara bertahap.</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Fokus untuk pengguna HP: isi satu langkah, lanjut, lalu unggah
            berkas pada tahap akhir.</p>

        @if (!$submitted)
            <div class="mt-5 space-y-3">
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200">
                    <div class="h-full rounded-full bg-[#1d4f45] transition-all duration-300"
                        style="width: {{ $progressWidth }}%"></div>
                </div>
                <div class="grid grid-cols-5 gap-2">
                    @foreach ($stepLabels as $index => $label)
                        <div wire:key="indicator-{{ $index }}"
                            class="rounded-2xl px-2 py-2 text-center text-[11px] font-medium {{ $step >= $index ? 'bg-[#e4f0ec] text-[#18352f]' : 'bg-slate-100 text-slate-500' }}">
                            {{ $label }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </header>

    @if ($submitted)
        <section class="rounded-[28px] border border-emerald-200 bg-emerald-50 px-5 py-6 shadow-sm">
            <p class="text-sm font-semibold text-emerald-700">Pendaftaran berhasil dikirim.</p>
            <h2 class="mt-2 text-xl font-bold text-slate-900">Nomor pendaftaran Anda: {{ $registrationNumber }}</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">Data formulir dan berkas pendukung sudah berhasil
                dikirimkan. Simpan nomor ini untuk konfirmasi ke panitia. Data akan diperiksa oleh admin sekolah.</p>
            <div
                class="mt-4 rounded-2xl border border-emerald-200 bg-white px-4 py-3 text-sm text-emerald-800 shadow-sm">
                Semua berkas utama telah diterima sistem: Akte Lahir, Kartu Keluarga, foto siswa, dan dokumen tambahan
                bila diunggah.
            </div>
            <div class="mt-5 flex flex-col gap-3">
                <a href="{{ route('ppdb.rekap-pdf', ['registrationNumber' => $registrationNumber]) }}" target="_blank"
                    rel="noopener"
                    class="inline-flex items-center justify-center rounded-2xl bg-[#18352f] px-4 py-3 text-sm font-semibold text-white">
                    Cetak PDF Rekap Pendaftaran
                </a>
                <a href="{{ route('ppdb.informasi') }}" wire:navigate
                    class="inline-flex items-center justify-center rounded-2xl bg-[#1d4f45] px-4 py-3 text-sm font-semibold text-white">
                    Kembali ke informasi SPMB
                </a>
            </div>
        </section>
    @else
        <section class="relative overflow-hidden rounded-[28px] border border-slate-200 bg-white px-5 py-5 shadow-sm">
            <div wire:loading wire:target="nextStep, previousStep"
                class="absolute inset-0 z-10 flex items-center justify-center bg-white/80 backdrop-blur-sm">
                <div
                    class="flex items-center gap-3 rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow">
                    <span
                        class="h-3 w-3 animate-spin rounded-full border-2 border-[#1d4f45] border-t-transparent"></span>
                    <span>Memproses langkah pendaftaran...</span>
                </div>
            </div>

            <div wire:loading wire:target="submitForm"
                class="absolute inset-0 z-10 flex items-center justify-center bg-white/90 backdrop-blur-sm">
                <div
                    class="w-full max-w-xs rounded-[28px] border border-emerald-100 bg-white px-5 py-5 text-center shadow-xl">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50">
                        <span
                            class="h-5 w-5 animate-spin rounded-full border-2 border-emerald-600 border-t-transparent"></span>
                    </div>
                    <h3 class="mt-4 text-base font-bold text-slate-900">Mengirim data dan berkas</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Mohon tunggu. Sistem sedang menyimpan formulir dan
                        mengunggah berkas pendaftaran Anda.</p>
                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200">
                        <div class="h-full w-full animate-pulse rounded-full bg-emerald-500"></div>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">Jangan tutup halaman sampai proses selesai.</p>
                </div>
            </div>

            @if ($step === 1)
                <div wire:key="ppdb-step-1" class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">NIK calon siswa</label>
                        <input type="text" inputmode="numeric" wire:model.live.blur="nik"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15"
                            placeholder="Masukkan 16 digit NIK">
                        <p class="mt-2 text-xs leading-5 text-slate-500">Langkah pertama hanya untuk memastikan NIK
                            belum pernah dipakai di sistem pendaftaran.</p>
                        @error('nik')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div
                        class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs leading-5 text-amber-700">
                        Jika NIK sudah terdaftar dan itu merupakan kekeliruan, silakan hubungi operator sekolah.
                    </div>
                </div>
            @elseif ($step === 2)
                <div wire:key="ppdb-step-2" class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama lengkap calon siswa</label>
                        <input type="text" wire:model.live.blur="name"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Tempat lahir</label>
                            <input type="text" wire:model.live.blur="birthPlace"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                            @error('birthPlace')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal lahir</label>
                            <input type="date" wire:model.live.blur="birthDate"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                            @error('birthDate')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">No. KK</label>
                            <input type="text" inputmode="numeric" wire:model.live.blur="familyCardNumber"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                            @error('familyCardNumber')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis kelamin</label>
                            <select wire:model.live="gender"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Agama</label>
                            <select wire:model.live="religion"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                            @error('religion')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @elseif ($step === 3)
                <div wire:key="ppdb-step-3" class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama ayah</label>
                        <input type="text" wire:model.live.blur="fatherName"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                        @error('fatherName')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama ibu</label>
                        <input type="text" wire:model.live.blur="motherName"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                        @error('motherName')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Pekerjaan ayah</label>
                            <input type="text" wire:model.live.blur="fatherOccupation"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                            @error('fatherOccupation')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Pekerjaan ibu</label>
                            <input type="text" wire:model.live.blur="motherOccupation"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                            @error('motherOccupation')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">No. HP ayah / wali</label>
                            <input type="text" inputmode="tel" wire:model.live.blur="fatherPhone"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                            @error('fatherPhone')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">No. HP ibu</label>
                            <input type="text" inputmode="tel" wire:model.live.blur="motherPhone"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15">
                            @error('motherPhone')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Alamat domisili</label>
                        <textarea rows="4" wire:model.live.blur="address"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15"></textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan tambahan</label>
                        <textarea rows="3" wire:model.live.blur="notes"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/15"></textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @elseif ($step === 4)
                <div wire:key="ppdb-step-4" class="space-y-4">
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Akte Lahir</label>
                        <input type="file" wire:model="birthCertificate" accept=".pdf,.jpg,.jpeg,.png"
                            class="block w-full text-sm text-slate-600">
                        <p class="mt-2 text-xs text-slate-500">Format: PDF/JPG/PNG. Maksimal 3 MB.</p>
                        @if ($this->selectedFileLabel($birthCertificate))
                            <p class="mt-2 text-xs font-semibold text-emerald-700">File terpilih:
                                {{ $this->selectedFileLabel($birthCertificate) }}</p>
                        @endif
                        @error('birthCertificate')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Kartu Keluarga</label>
                        <input type="file" wire:model="familyCard" accept=".pdf,.jpg,.jpeg,.png"
                            class="block w-full text-sm text-slate-600">
                        <p class="mt-2 text-xs text-slate-500">Format: PDF/JPG/PNG. Maksimal 3 MB.</p>
                        @if ($this->selectedFileLabel($familyCard))
                            <p class="mt-2 text-xs font-semibold text-emerald-700">File terpilih:
                                {{ $this->selectedFileLabel($familyCard) }}</p>
                        @endif
                        @error('familyCard')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Foto siswa latar merah</label>
                        <input type="file" wire:model="studentPhoto" accept=".jpg,.jpeg,.png"
                            class="block w-full text-sm text-slate-600">
                        <p class="mt-2 text-xs text-slate-500">Format: JPG/PNG. Maksimal 3 MB.</p>
                        @if ($this->selectedFileLabel($studentPhoto))
                            <p class="mt-2 text-xs font-semibold text-emerald-700">File terpilih:
                                {{ $this->selectedFileLabel($studentPhoto) }}</p>
                        @endif
                        @error('studentPhoto')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Ijazah TK (jika ada)</label>
                        <input type="file" wire:model="kindergartenCertificate" accept=".pdf,.jpg,.jpeg,.png"
                            class="block w-full text-sm text-slate-600">
                        <p class="mt-2 text-xs text-slate-500">Opsional. Format: PDF/JPG/PNG. Maksimal 3 MB.</p>
                        @if ($this->selectedFileLabel($kindergartenCertificate))
                            <p class="mt-2 text-xs font-semibold text-emerald-700">File terpilih:
                                {{ $this->selectedFileLabel($kindergartenCertificate) }}</p>
                        @endif
                        @error('kindergartenCertificate')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @else
                <div wire:key="ppdb-step-5" class="space-y-4 text-sm text-slate-600">
                    <div class="rounded-2xl bg-[#f7faf8] p-4">
                        <h2 class="text-base font-semibold text-slate-900">Periksa ringkasan data</h2>
                        <div class="mt-3 space-y-2 leading-6">
                            <p><span class="font-semibold text-slate-800">NIK:</span> {{ $nik }}</p>
                            <p><span class="font-semibold text-slate-800">Nama siswa:</span> {{ $name }}</p>
                            <p><span class="font-semibold text-slate-800">No. KK:</span> {{ $familyCardNumber }}</p>
                            <p><span class="font-semibold text-slate-800">Nama ayah:</span> {{ $fatherName }}</p>
                            <p><span class="font-semibold text-slate-800">Nama ibu:</span> {{ $motherName }}</p>
                            <p><span class="font-semibold text-slate-800">Kontak utama:</span> {{ $fatherPhone }}</p>
                            <p><span class="font-semibold text-slate-800">Akte lahir:</span>
                                {{ $this->selectedFileLabel($birthCertificate) ?? 'Belum dipilih' }}</p>
                            <p><span class="font-semibold text-slate-800">Kartu keluarga:</span>
                                {{ $this->selectedFileLabel($familyCard) ?? 'Belum dipilih' }}</p>
                            <p><span class="font-semibold text-slate-800">Foto siswa:</span>
                                {{ $this->selectedFileLabel($studentPhoto) ?? 'Belum dipilih' }}</p>
                        </div>
                    </div>
                    <p
                        class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs leading-5 text-amber-700">
                        Pastikan data dan dokumen sudah benar. Setelah dikirim, panitia akan memeriksa berkas Anda.</p>
                </div>
            @endif

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-between">
                <div>
                    @if ($step > 1)
                        <button type="button" wire:click="previousStep" wire:loading.attr="disabled"
                            wire:target="previousStep"
                            class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 sm:w-auto">
                            Kembali
                        </button>
                    @endif
                </div>

                <div>
                    @if ($step < 5)
                        <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                            wire:target="nextStep"
                            class="inline-flex w-full items-center justify-center rounded-2xl bg-[#1d4f45] px-4 py-3 text-sm font-semibold text-white disabled:opacity-60 sm:w-auto">
                            <span wire:loading.remove wire:target="nextStep">Lanjut ke langkah berikutnya</span>
                            <span wire:loading wire:target="nextStep">Memeriksa data...</span>
                        </button>
                    @else
                        <button type="button" wire:click="submitForm" wire:loading.attr="disabled"
                            wire:target="submitForm"
                            class="inline-flex w-full items-center justify-center rounded-2xl bg-[#f6c453] px-4 py-3 text-sm font-semibold text-[#18352f] disabled:opacity-60 sm:w-auto">
                            <span wire:loading.remove wire:target="submitForm">Kirim pendaftaran</span>
                            <span wire:loading wire:target="submitForm">Mengirim data dan berkas...</span>
                        </button>
                    @endif
                </div>
            </div>
        </section>
    @endif
</div>
