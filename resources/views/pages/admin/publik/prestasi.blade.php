@cannot ('update', $post)

@elsecannot ('create', $post)

@endcannot<?php

use App\Models\SchoolAchievement;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public string $title = '';

    public string $description = '';

    public string $level = '';

    public string $year = '';

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:100'],
            'year' => ['required', 'digits:4', 'integer', 'min:2000', 'max:2100'],
        ];
    }

    public function getItemsProperty(): Collection
    {
        return SchoolAchievement::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery
                        ->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('level', 'like', '%' . $this->search . '%')
                        ->orWhere('year', 'like', '%' . $this->search . '%');
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
        $item = SchoolAchievement::query()->findOrFail($id);

        $this->editingId = $item->id;
        $this->title = $item->title;
        $this->description = $item->description;
        $this->level = $item->level;
        $this->year = (string) $item->year;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        SchoolAchievement::query()->updateOrCreate(
            ['id' => $this->editingId],
            [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'level' => $validated['level'],
                'year' => (int) $validated['year'],
                'sort_order' => $this->editingId ? SchoolAchievement::query()->findOrFail($this->editingId)->sort_order : ((int) SchoolAchievement::query()->max('sort_order')) + 1,
            ],
        );

        $message = $this->editingId ? 'Prestasi berhasil diperbarui.' : 'Prestasi berhasil ditambahkan.';

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
        SchoolAchievement::query()->findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->showDeleteModal = false;
        $this->dispatch('toast', type: 'success', message: 'Prestasi berhasil dihapus.');
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
        $this->title = '';
        $this->description = '';
        $this->level = '';
        $this->year = '';
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Prestasi</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola daftar prestasi siswa yang tampil di halaman publik.
        </p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Prestasi</h2>
                <p class="mt-1 text-sm text-slate-500">Tambah, ubah, dan hapus prestasi yang akan ditampilkan di halaman
                    depan.</p>
            </div>
            <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                <span wire:loading.remove wire:target="openCreate">Tambah Prestasi</span>
                <span wire:loading wire:target="openCreate">Membuka...</span>
            </button>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Total</p>
                <p class="mt-2 text-2xl font-bold text-slate-800">{{ $this->items->count() }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-700">Tingkat Unik</p>
                <p class="mt-2 text-2xl font-bold text-slate-800">{{ $this->items->pluck('level')->unique()->count() }}
                </p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Tahun Terbaru</p>
                <p class="mt-2 text-2xl font-bold text-slate-800">{{ $this->items->max('year') ?? '-' }}</p>
            </div>
        </div>

        <div class="relative mt-4">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari judul, deskripsi, tingkat, atau tahun"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-24 text-sm" />
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Mencari...</span>
        </div>

        <div class="relative mt-4 overflow-hidden rounded-2xl border border-slate-200">
            <div wire:loading.flex wire:target="search,save,delete,openEdit,confirmDelete"
                class="absolute inset-0 z-10 hidden items-center justify-center bg-white/80 text-sm font-semibold text-slate-600">
                Memproses data prestasi...
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="px-4 py-3 font-medium">Judul</th>
                            <th class="px-4 py-3 font-medium">Deskripsi</th>
                            <th class="px-4 py-3 font-medium">Tingkat</th>
                            <th class="px-4 py-3 font-medium">Tahun</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->items as $item)
                            <tr wire:key="achievement-{{ $item->id }}" class="border-t border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $item->title }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $item->description }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $item->level }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $item->year }}</td>
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
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data
                                    prestasi.</td>
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
            <a href="{{ route('admin.publik.info-sekolah') }}" wire:navigate
                class="rounded-xl bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700">Info Sekolah</a>
            <a href="{{ route('admin.publik.visi-misi') }}" wire:navigate
                class="rounded-xl bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-700">Visi Misi</a>
            <a href="{{ route('admin.publik.berita') }}" wire:navigate
                class="rounded-xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Berita</a>
        </div>
    </section>

    @if ($showFormModal)
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:py-10"
            style="z-index: 120;">
            <div class="w-full max-w-2xl max-h-[calc(100vh-3rem)] overflow-y-auto rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            {{ $editingId ? 'Edit Prestasi' : 'Tambah Prestasi' }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">Isi data prestasi yang akan tampil di halaman depan
                            sekolah.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Judul Badge</label>
                        <input type="text" wire:model="title" placeholder="Contoh: Juara 1"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                        @error('title')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Deskripsi Prestasi</label>
                        <textarea wire:model="description" rows="4" placeholder="Contoh: Olimpiade Sains Nasional Tingkat Provinsi"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"></textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Tingkat</label>
                            <input type="text" wire:model="level" placeholder="Contoh: Provinsi"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('level')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Tahun</label>
                            <input type="number" wire:model="year" min="2000" max="2100"
                                placeholder="2026"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('year')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeModal"
                            class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
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
                <h2 class="text-lg font-bold text-slate-800">Hapus Prestasi</h2>
                <p class="mt-2 text-sm text-slate-600">Data prestasi yang dihapus tidak bisa dikembalikan.</p>

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
