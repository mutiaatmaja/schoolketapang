<?php

use App\Models\SpmbRegistration;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::ppdb.app')] class extends Component {
    use WithFileUploads;

    public int $step = 1;

    public bool $isSubmitted = false;

    public ?string $submittedRegistrationNumber = null;

    public ?string $submittedStudentName = null;

    public ?string $submittedAtLabel = null;

    public string $name = '';

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

    public $birthCertificate = null;

    public $familyCard = null;

    public $studentPhoto = null;

    public $kindergartenCertificate = null;

    public function mount(): void
    {
        $registration = SpmbRegistration::query()
            ->whereBelongsTo(auth()->user())
            ->first();

        if ($registration instanceof SpmbRegistration) {
            $this->isSubmitted = true;
            $this->submittedRegistrationNumber = $registration->registration_number;
            $this->submittedStudentName = $registration->name;
            $this->submittedAtLabel = optional($registration->submitted_at)->translatedFormat('d F Y, H:i');
        }
    }

    public function getStepItemsProperty(): Collection
    {
        return collect([['number' => 1, 'label' => 'Siswa'], ['number' => 2, 'label' => 'Orang tua'], ['number' => 3, 'label' => 'Berkas'], ['number' => 4, 'label' => 'Review']]);
    }

    public function getCompletionPercentageProperty(): int
    {
        return match ($this->step) {
            1 => 25,
            2 => 50,
            3 => 75,
            default => 100,
        };
    }

    public function nextStep(): void
    {
        $this->validate($this->rulesForStep($this->step));

        if ($this->step < 4) {
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
        $validated = $this->validate($this->allRules());
        $registrationNumber = $this->generateRegistrationNumber();

        $record = SpmbRegistration::query()->create([
            'user_id' => auth()->id(),
            'registration_number' => $registrationNumber,
            'name' => $validated['name'],
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
            'birth_certificate_path' => $this->storeDocument($this->birthCertificate, 'birth-certificates'),
            'family_card_path' => $this->storeDocument($this->familyCard, 'family-cards'),
            'student_photo_path' => $this->storeDocument($this->studentPhoto, 'student-photos'),
            'kindergarten_certificate_path' => $this->kindergartenCertificate ? $this->storeDocument($this->kindergartenCertificate, 'kindergarten-certificates') : null,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $this->submittedRegistrationNumber = $record->registration_number;
        $this->submittedStudentName = $record->name;
        $this->submittedAtLabel = optional($record->submitted_at)->translatedFormat('d F Y, H:i');
        $this->isSubmitted = true;
        $this->step = 4;

        $this->dispatch('toast', type: 'success', message: 'Pendaftaran berhasil dikirim. Simpan nomor pendaftaran Anda.');
    }

    protected function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'birthPlace' => ['required', 'string', 'max:100'],
                'birthDate' => ['required', 'date'],
                'nik' => ['required', 'digits:16', 'unique:spmb_registrations,nik'],
                'gender' => ['required', 'in:Laki-laki,Perempuan'],
                'religion' => ['required', 'string', 'max:50'],
            ],
            2 => [
                'fatherName' => ['required', 'string', 'min:3', 'max:255'],
                'motherName' => ['required', 'string', 'min:3', 'max:255'],
                'fatherOccupation' => ['nullable', 'string', 'max:255'],
                'motherOccupation' => ['nullable', 'string', 'max:255'],
                'fatherPhone' => ['nullable', 'string', 'max:30'],
                'motherPhone' => ['nullable', 'string', 'max:30'],
                'address' => ['nullable', 'string'],
                'notes' => ['nullable', 'string'],
            ],
            3 => [
                'birthCertificate' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
                'familyCard' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
                'studentPhoto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
                'kindergartenCertificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            ],
            default => [],
        };
    }

    protected function allRules(): array
    {
        return array_merge($this->rulesForStep(1), $this->rulesForStep(2), $this->rulesForStep(3));
    }

    private function generateRegistrationNumber(): string
    {
        do {
            $number = 'SPMB-' . now()->format('Y') . '-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (SpmbRegistration::query()->where('registration_number', $number)->exists());

        return $number;
    }

    private function storeDocument($file, string $directory): string
    {
        return $file->storeAs('spmb/' . now()->format('Y') . '/' . $directory, Str::uuid()->toString() . '.' . $file->extension(), 'public');
    }
};
?>

<div class="space-y-5">
    <section class="rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Form SPMB</p>
                <h1 class="mt-2 text-2xl font-bold leading-tight text-slate-900">Pendaftaran murid baru</h1>
                <p class="mt-2 text-sm leading-6 text-slate-500">Anda login sebagai <span
                        class="font-semibold text-slate-700">{{ auth()->user()->email }}</span>. Lengkapi data siswa dari
                    akun orang tua ini.</p>
            </div>
            <a href="{{ route('ppdb.informasi') }}" wire:navigate
                class="rounded-full bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">Info</a>
        </div>

        @if (!$isSubmitted)
            <div class="mt-5 space-y-3">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                    <span>Progress</span>
                    <span>{{ $this->completionPercentage }}%</span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-sky-600 transition-all duration-300"
                        style="width: {{ $this->completionPercentage }}%"></div>
                </div>
                <div class="grid grid-cols-4 gap-2 text-center">
                    @foreach ($this->stepItems as $item)
                        <div wire:key="wizard-step-{{ $item['number'] }}" class="space-y-1">
                            <div
                                class="mx-auto flex h-9 w-9 items-center justify-center rounded-full text-xs font-bold {{ $step >= $item['number'] ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                                {{ $item['number'] }}
                            </div>
                            <p
                                class="text-[11px] font-medium leading-4 {{ $step >= $item['number'] ? 'text-slate-700' : 'text-slate-400' }}">
                                {{ $item['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    @if ($isSubmitted)
        <section class="rounded-[28px] border border-emerald-200 bg-white p-5 shadow-sm">
            <div class="rounded-2xl bg-emerald-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700">Pendaftaran berhasil</p>
                <h2 class="mt-2 text-xl font-bold text-emerald-900">Nomor pendaftaran Anda</h2>
                <p
                    class="mt-3 rounded-2xl bg-white px-4 py-3 text-center text-lg font-bold tracking-[0.18em] text-emerald-800">
                    {{ $submittedRegistrationNumber }}</p>
                <p class="mt-3 text-sm font-semibold text-emerald-900">Calon siswa: {{ $submittedStudentName }}</p>
                @if ($submittedAtLabel)
                    <p class="mt-1 text-xs text-emerald-800">Dikirim pada {{ $submittedAtLabel }}</p>
                @endif
                <p class="mt-3 text-sm leading-6 text-emerald-800">Tim sekolah akan memverifikasi data dan berkas Anda.
                    Simpan nomor ini untuk keperluan tindak lanjut.</p>
            </div>
            <a href="{{ route('ppdb.informasi') }}" wire:navigate
                class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">
                Kembali ke informasi SPMB
            </a>
        </section>
    @else
        <section class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm">
            @if ($step === 1)
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Step 1. Data calon siswa</h2>
                        <p class="mt-1 text-sm text-slate-500">Isi data utama calon murid. Kelas, NIS, dan NISN tidak
                            diminta pada tahap pendaftaran awal.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nama siswa</label>
                            <input type="text" wire:model.blur="name"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('name')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Tempat lahir</label>
                                <input type="text" wire:model.blur="birthPlace"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                                @error('birthPlace')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Tanggal lahir</label>
                                <input type="date" wire:model.blur="birthDate"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                                @error('birthDate')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">NIK</label>
                            <input type="text" wire:model.blur="nik"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"
                                maxlength="16" />
                            @error('nik')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Jenis kelamin</label>
                                <select wire:model="gender"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Agama</label>
                                <input type="text" wire:model.blur="religion"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                                @error('religion')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($step === 2)
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Step 2. Data orang tua</h2>
                        <p class="mt-1 text-sm text-slate-500">Masukkan data ayah, ibu, serta alamat yang bisa dihubungi
                            tim sekolah.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Nama ayah</label>
                                <input type="text" wire:model.blur="fatherName"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                                @error('fatherName')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Nama ibu</label>
                                <input type="text" wire:model.blur="motherName"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                                @error('motherName')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Pekerjaan ayah</label>
                                <input type="text" wire:model.blur="fatherOccupation"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"
                                    placeholder="Opsional" />
                                @error('fatherOccupation')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Pekerjaan ibu</label>
                                <input type="text" wire:model.blur="motherOccupation"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"
                                    placeholder="Opsional" />
                                @error('motherOccupation')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">No. HP ayah</label>
                                <input type="text" wire:model.blur="fatherPhone"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"
                                    placeholder="Opsional" />
                                @error('fatherPhone')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">No. HP ibu</label>
                                <input type="text" wire:model.blur="motherPhone"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"
                                    placeholder="Opsional" />
                                @error('motherPhone')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Alamat</label>
                            <textarea wire:model.blur="address" rows="4"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" placeholder="Opsional"></textarea>
                            @error('address')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Keterangan tambahan</label>
                            <textarea wire:model.blur="notes" rows="4"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" placeholder="Opsional"></textarea>
                            @error('notes')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @elseif ($step === 3)
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Step 3. Upload berkas</h2>
                        <p class="mt-1 text-sm text-slate-500">Fokus unggah satu per satu. Semua berkas wajib kecuali
                            ijazah TK.</p>
                    </div>

                    <div class="space-y-4">
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            class="rounded-2xl border border-slate-200 p-4">
                            <label class="text-sm font-semibold text-slate-700">Akte Lahir</label>
                            <input type="file" wire:model="birthCertificate" accept=".jpg,.jpeg,.png,.pdf"
                                class="mt-2 block w-full text-sm text-slate-600" />
                            <p class="mt-2 text-xs text-slate-500">JPG, PNG, atau PDF. Maksimal 4MB.</p>
                            <p wire:loading wire:target="birthCertificate"
                                class="mt-2 text-xs font-medium text-sky-600">Mengunggah Akte Lahir...</p>
                            <div x-show="uploading" class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full bg-sky-600" :style="`width: ${progress}%`"></div>
                            </div>
                            @if ($birthCertificate)
                                <p class="mt-2 text-xs font-medium text-slate-700">
                                    {{ $birthCertificate->getClientOriginalName() }}</p>
                            @endif
                            @error('birthCertificate')
                                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            class="rounded-2xl border border-slate-200 p-4">
                            <label class="text-sm font-semibold text-slate-700">Kartu Keluarga</label>
                            <input type="file" wire:model="familyCard" accept=".jpg,.jpeg,.png,.pdf"
                                class="mt-2 block w-full text-sm text-slate-600" />
                            <p class="mt-2 text-xs text-slate-500">JPG, PNG, atau PDF. Maksimal 4MB.</p>
                            <p wire:loading wire:target="familyCard" class="mt-2 text-xs font-medium text-sky-600">
                                Mengunggah KK...</p>
                            <div x-show="uploading" class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full bg-sky-600" :style="`width: ${progress}%`"></div>
                            </div>
                            @if ($familyCard)
                                <p class="mt-2 text-xs font-medium text-slate-700">
                                    {{ $familyCard->getClientOriginalName() }}</p>
                            @endif
                            @error('familyCard')
                                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            class="rounded-2xl border border-slate-200 p-4">
                            <label class="text-sm font-semibold text-slate-700">Foto siswa latar merah</label>
                            <input type="file" wire:model="studentPhoto" accept=".jpg,.jpeg,.png"
                                class="mt-2 block w-full text-sm text-slate-600" />
                            <p class="mt-2 text-xs text-slate-500">JPG atau PNG. Maksimal 3MB.</p>
                            <p wire:loading wire:target="studentPhoto" class="mt-2 text-xs font-medium text-sky-600">
                                Mengunggah foto siswa...</p>
                            <div x-show="uploading" class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full bg-sky-600" :style="`width: ${progress}%`"></div>
                            </div>
                            @if ($studentPhoto)
                                <p class="mt-2 text-xs font-medium text-slate-700">
                                    {{ $studentPhoto->getClientOriginalName() }}</p>
                            @endif
                            @error('studentPhoto')
                                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            class="rounded-2xl border border-slate-200 p-4">
                            <label class="text-sm font-semibold text-slate-700">Ijazah TK (jika ada)</label>
                            <input type="file" wire:model="kindergartenCertificate" accept=".jpg,.jpeg,.png,.pdf"
                                class="mt-2 block w-full text-sm text-slate-600" />
                            <p class="mt-2 text-xs text-slate-500">Opsional. JPG, PNG, atau PDF. Maksimal 4MB.</p>
                            <p wire:loading wire:target="kindergartenCertificate"
                                class="mt-2 text-xs font-medium text-sky-600">Mengunggah ijazah TK...</p>
                            <div x-show="uploading" class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full bg-sky-600" :style="`width: ${progress}%`"></div>
                            </div>
                            @if ($kindergartenCertificate)
                                <p class="mt-2 text-xs font-medium text-slate-700">
                                    {{ $kindergartenCertificate->getClientOriginalName() }}</p>
                            @endif
                            @error('kindergartenCertificate')
                                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Step 4. Review data</h2>
                        <p class="mt-1 text-sm text-slate-500">Periksa kembali data sebelum dikirim. Setelah submit,
                            nomor pendaftaran akan dibuat otomatis.</p>
                    </div>

                    <div class="space-y-3 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                        <div class="flex items-start justify-between gap-3"><span>Nama siswa</span><strong
                                class="text-right text-slate-800">{{ $name }}</strong></div>
                        <div class="flex items-start justify-between gap-3"><span>NIK</span><strong
                                class="text-right text-slate-800">{{ $nik }}</strong></div>
                        <div class="flex items-start justify-between gap-3"><span>Tempat, tanggal lahir</span><strong
                                class="text-right text-slate-800">{{ $birthPlace }}, {{ $birthDate }}</strong>
                        </div>
                        <div class="flex items-start justify-between gap-3"><span>Orang tua</span><strong
                                class="text-right text-slate-800">{{ $fatherName }} / {{ $motherName }}</strong>
                        </div>
                        <div class="flex items-start justify-between gap-3"><span>No. HP</span><strong
                                class="text-right text-slate-800">{{ $fatherPhone ?: '-' }} /
                                {{ $motherPhone ?: '-' }}</strong></div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-4 text-sm text-slate-600">
                        <p class="font-semibold text-slate-800">Berkas yang terunggah</p>
                        <ul class="mt-3 space-y-2">
                            <li>Akte Lahir: <span
                                    class="font-medium text-slate-800">{{ $birthCertificate?->getClientOriginalName() }}</span>
                            </li>
                            <li>Kartu Keluarga: <span
                                    class="font-medium text-slate-800">{{ $familyCard?->getClientOriginalName() }}</span>
                            </li>
                            <li>Foto siswa: <span
                                    class="font-medium text-slate-800">{{ $studentPhoto?->getClientOriginalName() }}</span>
                            </li>
                            <li>Ijazah TK: <span
                                    class="font-medium text-slate-800">{{ $kindergartenCertificate?->getClientOriginalName() ?? 'Tidak diunggah' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </section>

        <section class="sticky bottom-3 z-20">
            <div class="rounded-3xl border border-slate-200 bg-white/95 p-3 shadow-lg backdrop-blur">
                <div class="flex gap-3">
                    @if ($step > 1)
                        <button type="button" wire:click="previousStep" wire:loading.attr="disabled"
                            wire:target="previousStep"
                            class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 disabled:opacity-60">
                            Kembali
                        </button>
                    @endif

                    @if ($step < 4)
                        <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                            wire:target="nextStep"
                            class="inline-flex flex-1 items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white disabled:opacity-60">
                            <span wire:loading.remove wire:target="nextStep">Lanjut ke step berikutnya</span>
                            <span wire:loading wire:target="nextStep">Memeriksa data...</span>
                        </button>
                    @else
                        <button type="button" wire:click="submitForm" wire:loading.attr="disabled"
                            wire:target="submitForm"
                            class="inline-flex flex-1 items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white disabled:opacity-60">
                            <span wire:loading.remove wire:target="submitForm">Kirim pendaftaran</span>
                            <span wire:loading wire:target="submitForm">Mengirim pendaftaran...</span>
                        </button>
                    @endif
                </div>
            </div>
        </section>
    @endif
</div>
