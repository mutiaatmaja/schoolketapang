<?php

use App\Models\Role;
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

    public string $displayName = '';

    public string $description = '';

    public string $originalName = '';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'alpha_dash', 'max:255', Rule::unique('roles', 'name')->ignore($this->editingId)],
            'displayName' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function getRolesProperty(): Collection
    {
        return Role::query()
            ->withCount('users')
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery
                        ->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('display_name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $role = Role::query()->findOrFail($id);

        $this->editingId = $role->id;
        $this->name = $role->name;
        $this->originalName = $role->name;
        $this->displayName = $role->display_name ?? '';
        $this->description = $role->description ?? '';
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId && $this->isSystemRole($this->originalName) && $validated['name'] !== $this->originalName) {
            $this->dispatch('toast', type: 'error', message: 'Nama role sistem tidak dapat diubah.');

            return;
        }

        Role::query()->updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $validated['name'],
                'display_name' => $validated['displayName'],
                'description' => $validated['description'] ?: null,
            ],
        );

        $message = $this->editingId ? 'Role berhasil diperbarui.' : 'Role baru berhasil ditambahkan.';

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
        $role = Role::query()->withCount('users')->findOrFail($this->deletingId);

        if ($this->isSystemRole($role->name)) {
            $this->dispatch('toast', type: 'error', message: 'Role sistem tidak dapat dihapus.');
            $this->showDeleteModal = false;
            $this->deletingId = null;

            return;
        }

        if ($role->users_count > 0) {
            $this->dispatch('toast', type: 'error', message: 'Role masih dipakai user dan tidak dapat dihapus.');
            $this->showDeleteModal = false;
            $this->deletingId = null;

            return;
        }

        $role->delete();

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('toast', type: 'success', message: 'Role berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->resetForm();
        $this->showFormModal = false;
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function isSystemRole(string $roleName): bool
    {
        return in_array($roleName, ['superadmin', 'admin'], true);
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->name = '';
        $this->displayName = '';
        $this->description = '';
        $this->originalName = '';
    }
};
?>

<div class="space-y-6">
    <section class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-outline-variant/15">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Kelola Role</p>
                <h1 class="mt-2 text-3xl font-bold text-on-surface">Atur role dan hak akses</h1>
                <p class="mt-2 max-w-2xl text-sm text-on-surface-variant">Gunakan role untuk membedakan akun admin utama,
                    operator, atau kebutuhan internal lain.</p>
            </div>
            <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                class="inline-flex items-center justify-center rounded-2xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-lg disabled:opacity-60">
                <span wire:loading.remove wire:target="openCreate">Tambah Role</span>
                <span wire:loading wire:target="openCreate">Membuka form...</span>
            </button>
        </div>
    </section>

    <section class="rounded-[28px] border border-outline-variant/20 bg-white p-6 shadow-sm">
        <div class="relative max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari role"
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
                    <span>Memuat data role...</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-outline-variant/20 text-sm">
                    <thead class="bg-surface-container-low">
                        <tr class="text-left text-on-surface-variant">
                            <th class="px-5 py-4 font-semibold">Nama Role</th>
                            <th class="px-5 py-4 font-semibold">Deskripsi</th>
                            <th class="px-5 py-4 font-semibold">Dipakai</th>
                            <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20 bg-white">
                        @forelse ($this->roles as $role)
                            <tr wire:key="role-{{ $role->id }}">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-on-surface">
                                        {{ $role->display_name ?: str($role->name)->headline() }}</p>
                                    <p class="mt-1 text-xs text-on-surface-variant">{{ $role->name }}</p>
                                </td>
                                <td class="px-5 py-4 text-on-surface-variant">{{ $role->description ?: '-' }}</td>
                                <td class="px-5 py-4 text-on-surface">{{ $role->users_count }} user</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" wire:click="openEdit({{ $role->id }})"
                                            wire:loading.attr="disabled" wire:target="openEdit({{ $role->id }})"
                                            class="rounded-xl border border-outline-variant/30 px-3 py-2 text-xs font-semibold text-on-surface disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="openEdit({{ $role->id }})">Edit</span>
                                            <span wire:loading wire:target="openEdit({{ $role->id }})">...</span>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $role->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDelete({{ $role->id }})"
                                            class="rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="confirmDelete({{ $role->id }})">Hapus</span>
                                            <span wire:loading
                                                wire:target="confirmDelete({{ $role->id }})">...</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-on-surface-variant">Belum
                                    ada role yang sesuai pencarian.</td>
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
                        <h2 class="text-xl font-bold text-on-surface">{{ $editingId ? 'Edit Role' : 'Tambah Role' }}
                        </h2>
                        <p class="mt-1 text-sm text-on-surface-variant">Gunakan nama role dengan format huruf kecil dan
                            tanda hubung bila diperlukan.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="rounded-full bg-surface-container p-2 text-on-surface-variant">✕</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">Nama sistem role</label>
                        <input type="text" wire:model.live.blur="name"
                            class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">Nama tampilan</label>
                        <input type="text" wire:model.live.blur="displayName"
                            class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10">
                        @error('displayName')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">Deskripsi</label>
                        <textarea rows="3" wire:model.live.blur="description"
                            class="w-full rounded-2xl border border-outline-variant/40 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10"></textarea>
                        @error('description')
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
                <h2 class="text-xl font-bold text-on-surface">Hapus role?</h2>
                <p class="mt-2 text-sm text-on-surface-variant">Role hanya dapat dihapus jika tidak dipakai user dan
                    bukan role sistem.</p>
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
