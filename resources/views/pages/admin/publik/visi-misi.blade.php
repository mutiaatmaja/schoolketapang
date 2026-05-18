<?php

use App\Models\VisionMission;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public string $type = 'misi';

    public string $content = '';

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:visi,misi'],
            'content' => ['required', 'string', 'max:2000'],
        ];
    }

    public function getRecordsProperty(): Collection
    {
        return VisionMission::query()
            ->when($this->search !== '', function ($query) {
                $query->where('content', 'like', '%' . $this->search . '%');
            })
            ->orderBy('type')
            ->orderBy('sort_order')
            ->get();
    }

    public function getVisionsProperty(): Collection
    {
        return $this->records->where('type', 'visi')->values();
    }

    public function getMissionsProperty(): Collection
    {
        return $this->records->where('type', 'misi')->values();
    }

    public function openCreateVision(): void
    {
        $this->resetForm();
        $this->type = 'visi';
        $this->showFormModal = true;
    }

    public function openCreateMission(): void
    {
        $this->resetForm();
        $this->type = 'misi';
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $record = VisionMission::query()->findOrFail($id);

        $this->editingId = $record->id;
        $this->type = $record->type;
        $this->content = $record->content;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($validated['type'] === 'visi') {
            $existingVision = VisionMission::query()->where('type', 'visi')->when($this->editingId, fn($query) => $query->whereKeyNot($this->editingId))->exists();

            if ($existingVision) {
                $this->addError('content', 'Visi hanya boleh satu. Edit visi yang sudah ada.');

                return;
            }
        }

        VisionMission::query()->updateOrCreate(
            ['id' => $this->editingId],
            $validated + [
                'sort_order' => $this->editingId ? VisionMission::query()->findOrFail($this->editingId)->sort_order : ((int) VisionMission::query()->where('type', $validated['type'])->max('sort_order')) + 1,
            ],
        );

        $message = $this->editingId ? 'Visi misi berhasil diperbarui.' : 'Visi misi berhasil ditambahkan.';

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
        VisionMission::query()->findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->showDeleteModal = false;
        $this->dispatch('toast', type: 'success', message: 'Visi misi berhasil dihapus.');
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
        $this->type = 'misi';
        $this->content = '';
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Visi & Misi</h1>
        <p class="mt-2 text-sm text-slate-600">Atur narasi visi dan misi yang ditampilkan di website sekolah.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Visi & Misi</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola satu visi utama dan daftar misi sekolah.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if ($this->visions->isEmpty())
                    <button type="button" wire:click="openCreateVision" wire:loading.attr="disabled"
                        wire:target="openCreateVision"
                        class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                        <span wire:loading.remove wire:target="openCreateVision">Tambah Visi</span>
                        <span wire:loading wire:target="openCreateVision">Membuka...</span>
                    </button>
                @endif
                <button type="button" wire:click="openCreateMission" wire:loading.attr="disabled"
                    wire:target="openCreateMission"
                    class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                    <span wire:loading.remove wire:target="openCreateMission">Tambah Misi</span>
                    <span wire:loading wire:target="openCreateMission">Membuka...</span>
                </button>
            </div>
        </div>

        <div class="relative mt-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari konten visi atau misi"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-24 text-sm" />
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Mencari...</span>
        </div>

        <div class="relative mt-6 space-y-6">
            <div wire:loading.flex wire:target="search,save,delete,openEdit,confirmDelete"
                class="absolute inset-0 z-10 hidden items-center justify-center rounded-2xl bg-white/80 text-sm font-semibold text-slate-600">
                Memproses data visi misi...
            </div>

            <div>
                <h3 class="text-sm font-bold uppercase tracking-wide text-sky-700">Visi</h3>
                <div class="mt-3 space-y-3">
                    @forelse ($this->visions as $item)
                        <article wire:key="vision-{{ $item->id }}"
                            class="rounded-2xl border border-sky-100 bg-sky-50 p-4 text-sm text-slate-700">
                            <p>{{ $item->content }}</p>
                            <div class="mt-4 flex gap-2">
                                <button type="button" wire:click="openEdit({{ $item->id }})"
                                    wire:loading.attr="disabled" wire:target="openEdit({{ $item->id }})"
                                    class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="openEdit({{ $item->id }})">Edit</span>
                                    <span wire:loading wire:target="openEdit({{ $item->id }})">Membuka...</span>
                                </button>
                                <button type="button" wire:click="confirmDelete({{ $item->id }})"
                                    wire:loading.attr="disabled" wire:target="confirmDelete({{ $item->id }})"
                                    class="rounded-lg bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 disabled:opacity-50">
                                    <span wire:loading.remove
                                        wire:target="confirmDelete({{ $item->id }})">Hapus</span>
                                    <span wire:loading
                                        wire:target="confirmDelete({{ $item->id }})">Menyiapkan...</span>
                                </button>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-4 text-sm text-slate-500">Belum
                            ada visi yang disimpan.</div>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold uppercase tracking-wide text-amber-700">Misi</h3>
                <div class="mt-3 space-y-3">
                    @forelse ($this->missions as $item)
                        <article wire:key="mission-{{ $item->id }}"
                            class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <p class="text-sm text-slate-700">{{ $item->content }}</p>
                                <div class="flex gap-2">
                                    <button type="button" wire:click="openEdit({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="openEdit({{ $item->id }})"
                                        class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700 disabled:opacity-50">
                                        <span wire:loading.remove
                                            wire:target="openEdit({{ $item->id }})">Edit</span>
                                        <span wire:loading
                                            wire:target="openEdit({{ $item->id }})">Membuka...</span>
                                    </button>
                                    <button type="button" wire:click="confirmDelete({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="confirmDelete({{ $item->id }})"
                                        class="rounded-lg bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 disabled:opacity-50">
                                        <span wire:loading.remove
                                            wire:target="confirmDelete({{ $item->id }})">Hapus</span>
                                        <span wire:loading
                                            wire:target="confirmDelete({{ $item->id }})">Menyiapkan...</span>
                                    </button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-4 text-sm text-slate-500">Belum
                            ada misi yang disimpan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Tautan Cepat</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.publik.info-sekolah') }}" wire:navigate
                class="rounded-xl bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700">Info Sekolah</a>
            <a href="{{ route('admin.publik.berita') }}" wire:navigate
                class="rounded-xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Berita</a>
            <a href="{{ route('admin.publik.prestasi') }}" wire:navigate
                class="rounded-xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Prestasi</a>
        </div>
    </section>

    @if ($showFormModal)
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:py-10"
            style="z-index: 120;">
            <div class="w-full max-w-2xl max-h-[calc(100vh-3rem)] overflow-y-auto rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">{{ $editingId ? 'Edit Konten' : 'Tambah Konten' }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">Pilih jenis konten dan simpan perubahan.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Jenis</label>
                        <select wire:model="type"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                            <option value="visi">Visi</option>
                            <option value="misi">Misi</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Konten</label>
                        <textarea wire:model="content" rows="5"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"></textarea>
                        @error('content')
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
                <h2 class="text-lg font-bold text-slate-800">Hapus Konten</h2>
                <p class="mt-2 text-sm text-slate-600">Konten yang dihapus tidak bisa dikembalikan.</p>

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
