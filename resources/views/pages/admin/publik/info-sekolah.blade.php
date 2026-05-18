<?php

use App\Models\SchoolInformation;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public string $label = '';

    public string $value = '';

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:100', Rule::unique('school_information', 'label')->ignore($this->editingId)],
            'value' => ['required', 'string', 'max:500'],
        ];
    }

    public function getItemsProperty(): Collection
    {
        return SchoolInformation::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery->where('label', 'like', '%' . $this->search . '%')->orWhere('value', 'like', '%' . $this->search . '%');
                });
            })
            ->ordered()
            ->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $item = SchoolInformation::query()->findOrFail($id);

        $this->editingId = $item->id;
        $this->label = $item->label;
        $this->value = $item->value;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        SchoolInformation::query()->updateOrCreate(
            ['id' => $this->editingId],
            $validated + [
                'sort_order' => $this->editingId ? SchoolInformation::query()->findOrFail($this->editingId)->sort_order : ((int) SchoolInformation::query()->max('sort_order')) + 1,
            ],
        );

        $message = $this->editingId ? 'Informasi sekolah berhasil diperbarui.' : 'Informasi sekolah berhasil ditambahkan.';

        $this->resetForm();
        $this->showFormModal = false;
        $this->dispatch('toast', type: 'success', message: $message);
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        SchoolInformation::query()->findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->showDeleteModal = false;
        $this->dispatch('toast', type: 'success', message: 'Informasi sekolah berhasil dihapus.');
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
        $this->label = '';
        $this->value = '';
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Informasi Sekolah</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola profil dasar sekolah yang ditampilkan di halaman publik.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Data Utama</h2>
                <p class="mt-1 text-sm text-slate-500">Tambah, ubah, dan hapus informasi sekolah.</p>
            </div>
            <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                <span wire:loading.remove wire:target="openCreate">Tambah Info</span>
                <span wire:loading wire:target="openCreate">Membuka...</span>
            </button>
        </div>

        <div class="relative mt-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari label atau isi informasi"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-24 text-sm" />
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Mencari...</span>
        </div>

        <div class="relative mt-4 overflow-hidden rounded-2xl border border-slate-200">
            <div wire:loading.flex wire:target="search,save,delete,openEdit,confirmDelete"
                class="absolute inset-0 z-10 hidden items-center justify-center bg-white/80 text-sm font-semibold text-slate-600">
                Memproses data informasi sekolah...
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Label</th>
                            <th class="px-4 py-3 font-medium">Nilai</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->items as $item)
                            <tr wire:key="school-info-{{ $item->id }}" class="border-t border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $item->label }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $item->value }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="openEdit({{ $item->id }})"
                                            wire:loading.attr="disabled" wire:target="openEdit({{ $item->id }})"
                                            class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="openEdit({{ $item->id }})">Edit</span>
                                            <span wire:loading
                                                wire:target="openEdit({{ $item->id }})">Membuka...</span>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDelete({{ $item->id }})"
                                            class="rounded-lg bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="confirmDelete({{ $item->id }})">Hapus</span>
                                            <span wire:loading
                                                wire:target="confirmDelete({{ $item->id }})">Menyiapkan...</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data
                                    informasi sekolah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Tautan Cepat</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.publik.visi-misi') }}" wire:navigate
                class="rounded-xl bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700">Visi Misi</a>
            <a href="{{ route('admin.publik.berita') }}" wire:navigate
                class="rounded-xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Berita</a>
            <a href="{{ route('admin.publik.prestasi') }}" wire:navigate
                class="rounded-xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Prestasi</a>
        </div>
    </section>

    @if ($showFormModal)
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:py-10"
            style="z-index: 120;">
            <div class="w-full max-w-lg max-h-[calc(100vh-3rem)] overflow-y-auto rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            {{ $editingId ? 'Edit Info Sekolah' : 'Tambah Info Sekolah' }}</h2>
                        <p class="mt-1 text-sm text-slate-500">Lengkapi label dan nilai informasi yang ingin
                            ditampilkan.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Label</label>
                        <input type="text" wire:model="label"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                        @error('label')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Nilai</label>
                        <textarea wire:model="value" rows="4" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"></textarea>
                        @error('value')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeModal"
                            class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Batal</button>
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
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:items-center sm:py-10"
            style="z-index: 120;">
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-bold text-slate-800">Hapus Informasi</h2>
                <p class="mt-2 text-sm text-slate-600">Data yang dihapus tidak bisa dikembalikan.</p>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="closeModal"
                        class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Batal</button>
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
