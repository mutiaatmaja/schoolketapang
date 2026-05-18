<?php

use App\Models\SpmbRegistration;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

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

    public string $status = 'submitted';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'birthPlace' => ['required', 'string', 'max:100'],
            'birthDate' => ['required', 'date', 'before_or_equal:today'],
            'nik' => ['required', 'digits:16', Rule::unique('spmb_registrations', 'nik')->ignore($this->editingId)],
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
            'status' => ['required', 'in:submitted,verified,lulus,cadangan,ditolak'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.unique' => 'NIK sudah dipakai oleh peserta lain.',
        ];
    }

    public function getApplicantsProperty(): Collection
    {
        return SpmbRegistration::query()
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery
                        ->where('registration_number', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('family_card_number', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $registration = SpmbRegistration::query()->findOrFail($id);

        $this->editingId = $registration->id;
        $this->name = $registration->name;
        $this->birthPlace = $registration->birth_place;
        $this->birthDate = $registration->birth_date?->format('Y-m-d') ?? '';
        $this->nik = $registration->nik;
        $this->familyCardNumber = $registration->family_card_number;
        $this->gender = $registration->gender;
        $this->religion = $registration->religion;
        $this->fatherName = $registration->father_name;
        $this->motherName = $registration->mother_name;
        $this->fatherOccupation = $registration->father_occupation ?? '';
        $this->motherOccupation = $registration->mother_occupation ?? '';
        $this->fatherPhone = $registration->father_phone;
        $this->motherPhone = $registration->mother_phone ?? '';
        $this->address = $registration->address;
        $this->notes = $registration->notes ?? '';
        $this->status = $registration->status;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $currentRegistration = $this->editingId ? SpmbRegistration::query()->findOrFail($this->editingId) : null;

        SpmbRegistration::query()->updateOrCreate(
            ['id' => $this->editingId],
            [
                'registration_number' => $currentRegistration?->registration_number ?? $this->generateRegistrationNumber(),
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
                'status' => $validated['status'],
                'submitted_at' => $currentRegistration?->submitted_at ?? now(),
            ],
        );

        $message = $this->editingId ? 'Data peserta berhasil diperbarui.' : 'Peserta berhasil ditambahkan secara manual.';

        $this->closeModal();
        $this->dispatch('toast', type: 'success', message: $message);
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        SpmbRegistration::query()->findOrFail($this->deletingId)->delete();

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('toast', type: 'success', message: 'Data peserta berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->resetForm();
        $this->showFormModal = false;
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->name = '';
        $this->birthPlace = '';
        $this->birthDate = '';
        $this->nik = '';
        $this->familyCardNumber = '';
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
        $this->status = 'submitted';
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
    $statusStyles = [
        'submitted' => 'bg-amber-100 text-amber-800',
        'verified' => 'bg-sky-100 text-sky-800',
        'lulus' => 'bg-emerald-100 text-emerald-800',
        'cadangan' => 'bg-violet-100 text-violet-800',
        'ditolak' => 'bg-rose-100 text-rose-800',
    ];
@endphp

<div class="space-y-6 {{ $showFormModal || $showDeleteModal ? 'relative' : '' }}"
    @if ($showFormModal || $showDeleteModal) style="z-index: 70;" @endif>
    <section class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-outline-variant/15">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Admin SPMB</p>
                <h1 class="mt-2 text-3xl font-bold text-on-surface">Semua Peserta</h1>
                <p class="mt-2 max-w-2xl text-sm text-on-surface-variant">Lihat peserta yang mendaftar dari halaman
                    publik dan tambahkan peserta secara manual jika diperlukan.</p>
            </div>
            <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                class="inline-flex items-center justify-center rounded-2xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-lg disabled:opacity-60">
                <span wire:loading.remove wire:target="openCreate">Tambah Peserta Manual</span>
                <span wire:loading wire:target="openCreate">Membuka form...</span>
            </button>
        </div>
    </section>

    <section class="rounded-[28px] border border-outline-variant/20 bg-white p-6 shadow-sm">
        <div class="relative max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari nomor, nama, NIK, No. KK, atau status"
                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 pr-12 text-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
            <div wire:loading wire:target="search" class="absolute inset-y-0 right-4 flex items-center">
                <span class="h-4 w-4 animate-spin rounded-full border-2 border-primary border-t-transparent"></span>
            </div>
        </div>

        <div class="relative mt-6 overflow-hidden rounded-3xl border border-outline-variant/20">
            <div wire:loading wire:target="search, save, delete, openEdit, confirmDelete"
                class="absolute inset-0 z-10 flex items-center justify-center bg-white/80 backdrop-blur-sm">
                <div
                    class="flex items-center gap-3 rounded-full bg-white px-4 py-2 text-sm font-semibold text-on-surface shadow-lg">
                    <span class="h-3 w-3 animate-spin rounded-full border-2 border-primary border-t-transparent"></span>
                    <span>Memuat data peserta...</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-outline-variant/20 text-sm">
                    <thead class="bg-surface-container-low">
                        <tr class="text-left text-on-surface-variant">
                            <th class="px-5 py-4 font-semibold">Nomor</th>
                            <th class="px-5 py-4 font-semibold">Nama</th>
                            <th class="px-5 py-4 font-semibold">NIK</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20 bg-white">
                        @forelse ($this->applicants as $applicant)
                            <tr wire:key="applicant-{{ $applicant->id }}">
                                <td class="px-5 py-4 font-semibold text-on-surface">
                                    {{ $applicant->registration_number }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-on-surface">{{ $applicant->name }}</p>
                                    <p class="mt-1 text-xs text-on-surface-variant">
                                        {{ $applicant->submitted_at?->format('d M Y H:i') ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-on-surface-variant">{{ $applicant->nik }}</td>
                                <td class="px-5 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$applicant->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ str($applicant->status)->headline() }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.ppdb.pendaftar.detail', ['registration' => $applicant->registration_number]) }}"
                                            wire:navigate
                                            class="rounded-xl border border-outline-variant/30 px-3 py-2 text-xs font-semibold text-on-surface">Detail</a>
                                        <button type="button" wire:click="openEdit({{ $applicant->id }})"
                                            wire:loading.attr="disabled" wire:target="openEdit({{ $applicant->id }})"
                                            class="rounded-xl border border-outline-variant/30 px-3 py-2 text-xs font-semibold text-on-surface disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="openEdit({{ $applicant->id }})">Edit</span>
                                            <span wire:loading wire:target="openEdit({{ $applicant->id }})">...</span>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $applicant->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDelete({{ $applicant->id }})"
                                            class="rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="confirmDelete({{ $applicant->id }})">Hapus</span>
                                            <span wire:loading
                                                wire:target="confirmDelete({{ $applicant->id }})">...</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-on-surface-variant">Belum
                                    ada peserta yang sesuai pencarian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @if ($showFormModal)
        <div class="fixed inset-0 flex items-center justify-center bg-slate-900/55 px-4 py-6" style="z-index: 80;">
            <div class="max-h-[92vh] w-full max-w-4xl overflow-y-auto rounded-[28px] bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-on-surface">
                            {{ $editingId ? 'Edit Peserta' : 'Tambah Peserta Manual' }}</h2>
                        <p class="mt-1 text-sm text-on-surface-variant">Form ini menggunakan data yang sama dengan
                            pendaftaran publik.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="rounded-full bg-surface-container p-2 text-on-surface-variant">✕</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Nama lengkap</label>
                            <input type="text" wire:model.live.blur="name"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">NIK</label>
                            <input type="text" inputmode="numeric" wire:model.live.blur="nik"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('nik')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Tempat lahir</label>
                            <input type="text" wire:model.live.blur="birthPlace"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('birthPlace')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Tanggal lahir</label>
                            <input type="date" wire:model.live.blur="birthDate"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('birthDate')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">No. KK</label>
                            <input type="text" inputmode="numeric" wire:model.live.blur="familyCardNumber"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('familyCardNumber')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Jenis kelamin</label>
                            <select wire:model.live="gender"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Agama</label>
                            <select wire:model.live="religion"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                            @error('religion')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Status</label>
                            <select wire:model.live="status"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                                <option value="submitted">Submitted</option>
                                <option value="verified">Verified</option>
                                <option value="lulus">Lulus</option>
                                <option value="cadangan">Cadangan</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Nama ayah</label>
                            <input type="text" wire:model.live.blur="fatherName"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('fatherName')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Nama ibu</label>
                            <input type="text" wire:model.live.blur="motherName"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('motherName')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Pekerjaan ayah</label>
                            <input type="text" wire:model.live.blur="fatherOccupation"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('fatherOccupation')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">Pekerjaan ibu</label>
                            <input type="text" wire:model.live.blur="motherOccupation"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('motherOccupation')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">No. HP ayah / wali</label>
                            <input type="text" wire:model.live.blur="fatherPhone"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('fatherPhone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface">No. HP ibu</label>
                            <input type="text" wire:model.live.blur="motherPhone"
                                class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            @error('motherPhone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">Alamat</label>
                        <textarea rows="4" wire:model.live.blur="address"
                            class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10"></textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">Catatan</label>
                        <textarea rows="3" wire:model.live.blur="notes"
                            class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10"></textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                        <button type="button" wire:click="closeModal"
                            class="rounded-2xl border border-outline-variant/30 px-4 py-3 text-sm font-semibold text-on-surface-variant">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="rounded-2xl bg-primary px-4 py-3 text-sm font-semibold text-white disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">Simpan</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 flex items-center justify-center bg-slate-900/55 px-4 py-6" style="z-index: 80;">
            <div class="w-full max-w-md rounded-[28px] bg-white p-6 shadow-2xl">
                <h2 class="text-xl font-bold text-on-surface">Hapus peserta?</h2>
                <p class="mt-2 text-sm text-on-surface-variant">Peserta yang dihapus akan hilang dari daftar SPMB
                    admin.</p>
                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="closeModal"
                        class="rounded-2xl border border-outline-variant/30 px-4 py-3 text-sm font-semibold text-on-surface-variant">Batal</button>
                    <button type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete"
                        class="rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white disabled:opacity-60">
                        <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                        <span wire:loading wire:target="delete">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
