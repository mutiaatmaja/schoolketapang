<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public function rules(): array
    {
        $passwordRequired = $this->editingId === null;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'password' => [$passwordRequired ? 'required' : 'nullable', 'nullable', 'string', 'min:8'],
            'passwordConfirmation' => [$passwordRequired || $this->password !== '' ? 'required' : 'nullable', 'nullable', 'same:password'],
        ];
    }

    public function getParentsProperty(): Collection
    {
        return User::query()
            ->whereHas('roles', function ($query): void {
                $query->where('name', 'orang_tua');
            })
            ->with([
                'spmbRegistrations' => function ($query): void {
                    $query->select('id', 'user_id', 'registration_number', 'name', 'status', 'submitted_at')->latest('submitted_at')->latest('id');
                },
            ])
            ->withCount('spmbRegistrations')
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('id')
            ->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $parent = $this->findParent($id);

        $this->editingId = $parent->id;
        $this->name = $parent->name;
        $this->email = $parent->email;
        $this->password = '';
        $this->passwordConfirmation = '';
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();
        $role = Role::query()->firstOrCreate(['name' => 'orang_tua'], ['display_name' => 'Orang Tua', 'description' => 'Akun orang tua untuk mengelola pendaftaran SPMB.']);

        $parent = $this->editingId ? $this->findParent($this->editingId) : new User();

        $parent->name = $validated['name'];
        $parent->email = $validated['email'];

        if ($validated['password'] !== '') {
            $parent->password = $validated['password'];
        }

        $parent->save();
        $parent->syncRoles([$role->name]);

        $message = $this->editingId ? 'Akun orang tua berhasil diperbarui.' : 'Akun orang tua berhasil ditambahkan.';

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
        $this->findParent($this->deletingId)->delete();

        $this->deletingId = null;
        $this->showDeleteModal = false;
        $this->dispatch('toast', type: 'success', message: 'Akun orang tua berhasil dihapus.');
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
        $this->email = '';
        $this->password = '';
        $this->passwordConfirmation = '';
    }

    private function findParent(?int $id): User
    {
        return User::query()
            ->whereHas('roles', function ($query): void {
                $query->where('name', 'orang_tua');
            })
            ->findOrFail($id);
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin SPMB</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Akun Orang Tua</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola akun parent yang dipakai untuk mengirim data calon siswa SPMB.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Akun Orang Tua</h2>
                <p class="mt-1 text-sm text-slate-500">Lihat jumlah anak yang didaftarkan, ubah akun, hapus akun, atau
                    buka detail anak.</p>
            </div>
            <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                <span wire:loading.remove wire:target="openCreate">Tambah Akun Orang Tua</span>
                <span wire:loading wire:target="openCreate">Membuka...</span>
            </button>
        </div>

        <div class="relative mt-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email orang tua"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-24 text-sm" />
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Mencari...</span>
        </div>

        <div class="relative mt-4 overflow-hidden rounded-2xl border border-slate-200">
            <div wire:loading.flex wire:target="search,save,delete,openEdit,confirmDelete,openCreate"
                class="absolute inset-0 z-10 hidden items-center justify-center bg-white/80 text-sm font-semibold text-slate-600">
                Memproses akun orang tua...
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[920px] text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Orang Tua</th>
                            <th class="px-4 py-3 font-medium">Anak / Pendaftaran</th>
                            <th class="px-4 py-3 font-medium">Status Terakhir</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->parents as $parent)
                            <tr wire:key="parent-{{ $parent->id }}" class="border-t border-slate-100 align-top">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-800">{{ $parent->name }}</p>
                                    <p class="mt-1 text-slate-600">{{ $parent->email }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p class="font-medium text-slate-700">{{ $parent->spmb_registrations_count }}
                                        pendaftaran</p>
                                    @if ($parent->spmbRegistrations->isNotEmpty())
                                        <div class="mt-2 space-y-1">
                                            @foreach ($parent->spmbRegistrations->take(2) as $registration)
                                                <p wire:key="parent-registration-{{ $registration->id }}"
                                                    class="text-xs text-slate-500">
                                                    {{ $registration->name }} · {{ $registration->registration_number }}
                                                </p>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="mt-1 text-xs text-slate-500">Belum ada anak yang didaftarkan.</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    @php($latestRegistration = $parent->spmbRegistrations->first())
                                    @if ($latestRegistration)
                                        <span
                                            class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $latestRegistration->status }}
                                        </span>
                                        <p class="mt-2 text-xs text-slate-500">
                                            {{ $latestRegistration->submitted_at?->format('d M Y H:i') }}</p>
                                    @else
                                        <span class="text-xs text-slate-500">Belum ada pengajuan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.ppdb.orang-tua.show', $parent) }}" wire:navigate
                                            class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700">
                                            Detail Anak
                                        </a>
                                        <button type="button" wire:click="openEdit({{ $parent->id }})"
                                            wire:loading.attr="disabled" wire:target="openEdit({{ $parent->id }})"
                                            class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="openEdit({{ $parent->id }})">Edit</span>
                                            <span wire:loading
                                                wire:target="openEdit({{ $parent->id }})">Membuka...</span>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $parent->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDelete({{ $parent->id }})"
                                            class="rounded-lg bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="confirmDelete({{ $parent->id }})">Hapus</span>
                                            <span wire:loading
                                                wire:target="confirmDelete({{ $parent->id }})">Menyiapkan...</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada akun orang
                                    tua.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @if ($showFormModal)
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:py-10"
            style="z-index: 120;">
            <div class="w-full max-w-2xl max-h-[calc(100vh-3rem)] overflow-y-auto rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            {{ $editingId ? 'Edit Akun Orang Tua' : 'Tambah Akun Orang Tua' }}</h2>
                        <p class="mt-1 text-sm text-slate-500">Gunakan email aktif. Kosongkan kata sandi jika tidak
                            ingin menggantinya saat edit.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-5">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Nama Orang Tua</label>
                        <input type="text" wire:model="name"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" wire:model="email"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                        @error('email')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Kata Sandi</label>
                            <input type="password" wire:model="password"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('password')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Konfirmasi Kata Sandi</label>
                            <input type="password" wire:model="passwordConfirmation"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('passwordConfirmation')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">Simpan</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 flex items-center justify-center bg-slate-900/50 px-4" style="z-index: 120;">
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-bold text-slate-800">Hapus akun orang tua?</h2>
                <p class="mt-2 text-sm text-slate-500">Akun parent dan data pendaftaran anak yang terhubung akan ikut
                    terhapus.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="closeModal"
                        class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Batal</button>
                    <button type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete"
                        class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                        <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                        <span wire:loading wire:target="delete">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
