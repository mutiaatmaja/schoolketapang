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

    public string $selectedRole = '';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'password' => [$this->editingId ? 'nullable' : 'required', 'string', 'min:8'],
            'selectedRole' => ['required', 'string', 'exists:roles,name'],
        ];
    }

    public function getUsersProperty(): Collection
    {
        return User::query()
            ->with('roles:id,name,display_name')
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery
                        ->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhereHas('roles', function ($roleQuery): void {
                            $roleQuery->where('name', 'like', '%' . $this->search . '%')->orWhere('display_name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->get();
    }

    public function getRolesProperty(): Collection
    {
        return Role::query()->orderBy('display_name')->orderBy('name')->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::query()->with('roles:id,name')->findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRole = $user->roles->pluck('name')->first() ?? '';
        $this->password = '';
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($validated['password'] !== '') {
            $payload['password'] = $validated['password'];
        }

        $user = User::query()->updateOrCreate(['id' => $this->editingId], $payload);

        $user->syncRoles([$validated['selectedRole']]);

        $message = $this->editingId ? 'Data user berhasil diperbarui.' : 'User baru berhasil ditambahkan.';

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
        $user = User::query()->findOrFail($this->deletingId);

        if ($user->is(auth()->user())) {
            $this->dispatch('toast', type: 'error', message: 'Akun yang sedang dipakai tidak dapat dihapus.');
            $this->showDeleteModal = false;
            $this->deletingId = null;

            return;
        }

        $user->delete();

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('toast', type: 'success', message: 'User berhasil dihapus.');
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
        $this->selectedRole = '';
    }
};
?>

<div class="space-y-6">
    <section class="rounded-[28px] p-8 text-white shadow-xl"
        style="background-image: linear-gradient(to right, #00288e, #1e40af);">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/70">Kelola User</p>
                <h1 class="mt-2 text-3xl font-bold">Pengelolaan akun internal</h1>
                <p class="mt-2 max-w-2xl text-sm text-white/85">Atur user dan role yang dapat mengakses area administrasi
                    sekolah.</p>
            </div>
            <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                class="inline-flex items-center justify-center rounded-2xl bg-secondary-container px-5 py-3 text-sm font-semibold text-on-secondary-container shadow-lg disabled:opacity-60">
                <span wire:loading.remove wire:target="openCreate">Tambah User</span>
                <span wire:loading wire:target="openCreate">Membuka form...</span>
            </button>
        </div>
    </section>

    <section class="rounded-[28px] border border-outline-variant/20 bg-white p-6 shadow-sm">
        <div class="relative max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, email, atau role"
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
                    <span>Memuat data user...</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-outline-variant/20 text-sm">
                    <thead class="bg-surface-container-low">
                        <tr class="text-left text-on-surface-variant">
                            <th class="px-5 py-4 font-semibold">Nama</th>
                            <th class="px-5 py-4 font-semibold">Email</th>
                            <th class="px-5 py-4 font-semibold">Role</th>
                            <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20 bg-white">
                        @forelse ($this->users as $user)
                            <tr wire:key="user-{{ $user->id }}">
                                <td class="px-5 py-4 font-semibold text-on-surface">{{ $user->name }}</td>
                                <td class="px-5 py-4 text-on-surface-variant">{{ $user->email }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse ($user->roles as $role)
                                            <span
                                                class="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                                                {{ $role->display_name ?: str($role->name)->headline() }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-on-surface-variant">Belum ada role</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" wire:click="openEdit({{ $user->id }})"
                                            wire:loading.attr="disabled" wire:target="openEdit({{ $user->id }})"
                                            class="rounded-xl border border-outline-variant/30 px-3 py-2 text-xs font-semibold text-on-surface disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="openEdit({{ $user->id }})">Edit</span>
                                            <span wire:loading wire:target="openEdit({{ $user->id }})">...</span>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $user->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDelete({{ $user->id }})"
                                            class="rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="confirmDelete({{ $user->id }})">Hapus</span>
                                            <span wire:loading
                                                wire:target="confirmDelete({{ $user->id }})">...</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-on-surface-variant">Belum
                                    ada user yang sesuai pencarian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @if ($showFormModal)
        <div class="fixed inset-0 flex items-center justify-center bg-slate-900/55 px-4 py-6" style="z-index: 999;">
            <div class="w-full max-w-lg rounded-[28px] bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-on-surface">{{ $editingId ? 'Edit User' : 'Tambah User' }}
                        </h2>
                        <p class="mt-1 text-sm text-on-surface-variant">Isi data akun dan pilih role yang sesuai.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="rounded-full bg-surface-container p-2 text-on-surface-variant">✕</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">Nama</label>
                        <input type="text" wire:model.live.blur="name"
                            class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">Email</label>
                        <input type="email" wire:model.live.blur="email"
                            class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">Password
                            {{ $editingId ? '(kosongkan jika tidak diubah)' : '' }}</label>
                        <input type="password" wire:model.live.blur="password"
                            class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">Role</label>
                        <select wire:model.live="selectedRole"
                            class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                            <option value="">Pilih role</option>
                            @foreach ($this->roles as $role)
                                <option value="{{ $role->name }}">
                                    {{ $role->display_name ?: str($role->name)->headline() }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole')
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
        <div class="fixed inset-0 flex items-center justify-center bg-slate-900/55 px-4 py-6" style="z-index: 999;">
            <div class="w-full max-w-md rounded-[28px] bg-white p-6 shadow-2xl">
                <h2 class="text-xl font-bold text-on-surface">Hapus user?</h2>
                <p class="mt-2 text-sm text-on-surface-variant">User yang dihapus tidak akan bisa login lagi ke area
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
