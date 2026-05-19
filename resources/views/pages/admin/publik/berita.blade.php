<?php

use App\Models\NewsArticle;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public string $title = '';

    public string $category = 'Pengumuman';

    public string $excerpt = '';

    public string $content = '';

    public string $status = 'draft';

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
        ];
    }

    public function getArticlesProperty(): Collection
    {
        return NewsArticle::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery
                        ->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('category', 'like', '%' . $this->search . '%')
                        ->orWhere('excerpt', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
        $this->dispatch('update-jodit-content', ['news-content-editor', $this->content]);
    }

    public function openEdit(int $id): void
    {
        $article = NewsArticle::query()->findOrFail($id);

        $this->editingId = $article->id;
        $this->title = $article->title;
        $this->category = $article->category;
        $this->excerpt = $article->excerpt ?? '';
        $this->content = $article->content;
        $this->status = $article->status;
        $this->showFormModal = true;
        $this->dispatch('update-jodit-content', ['news-content-editor', $this->content]);
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['content'] = $this->sanitizeContent($validated['content']);

        NewsArticle::query()->updateOrCreate(
            ['id' => $this->editingId],
            $validated + [
                'slug' => $this->generateUniqueSlug($validated['title']),
                'published_at' => $validated['status'] === 'published' ? now() : null,
            ],
        );

        $message = $this->editingId ? 'Berita berhasil diperbarui.' : 'Berita berhasil ditambahkan.';

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
        NewsArticle::query()->findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->showDeleteModal = false;
        $this->dispatch('toast', type: 'success', message: 'Berita berhasil dihapus.');
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
        $this->category = 'Pengumuman';
        $this->excerpt = '';
        $this->content = '';
        $this->status = 'draft';
    }

    private function sanitizeContent(string $content): string
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML.Allowed', 'p,br,strong,em,u,s,blockquote,ul,ol,li,a[href|target|rel],img[src|alt|title|width|height],h1,h2,h3,h4,h5,h6,table,thead,tbody,tr,th,td,hr');
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('CSS.AllowedProperties', ['text-align', 'width', 'height']);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
        ]);

        return new \HTMLPurifier($config)->purify($content);
    }

    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'berita';
        $slug = $baseSlug;
        $counter = 2;

        while (NewsArticle::query()->where('slug', $slug)->when($this->editingId, fn($query) => $query->whereKeyNot($this->editingId))->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Berita</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola berita yang tampil pada website publik sekolah.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-lg font-bold text-slate-800">Jumlah Berita</h2>
            <div class="flex items-center gap-3">
                <span
                    class="rounded-xl bg-sky-100 px-3 py-1 text-sm font-bold text-sky-700">{{ $this->articles->count() }}
                    item</span>
                <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                    class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                    <span wire:loading.remove wire:target="openCreate">Tambah Berita</span>
                    <span wire:loading wire:target="openCreate">Membuka...</span>
                </button>
            </div>
        </div>

        <div class="relative mt-4">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari judul, kategori, atau ringkasan"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-24 text-sm" />
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Mencari...</span>
        </div>

        <div class="relative mt-4 overflow-hidden rounded-2xl border border-slate-200">
            <div wire:loading.flex wire:target="search,save,delete,openEdit,confirmDelete"
                class="absolute inset-0 z-10 hidden items-center justify-center bg-white/80 text-sm font-semibold text-slate-600">
                Memproses data berita...
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[880px] text-left text-sm">
                    <thead class="bg-slate-50">
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="px-4 py-3 font-medium">Judul</th>
                            <th class="px-4 py-3 font-medium">Kategori</th>
                            <th class="px-4 py-3 font-medium">Tanggal</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->articles as $item)
                            <tr wire:key="berita-{{ $item->id }}" class="border-t border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-700">{{ $item->title }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $item->category }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $item->published_at?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="rounded-full px-2 py-1 text-xs font-semibold {{ $item->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $item->status === 'published' ? 'Terbit' : 'Draft' }}
                                    </span>
                                </td>
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
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada
                                    berita.</td>
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
            <a href="{{ route('admin.publik.prestasi') }}" wire:navigate
                class="rounded-xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Prestasi</a>
        </div>
    </section>

    @if ($showFormModal)
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:py-10"
            style="z-index: 120;">
            <div class="w-full max-w-3xl max-h-[calc(100vh-3rem)] overflow-y-auto rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">{{ $editingId ? 'Edit Berita' : 'Tambah Berita' }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">Lengkapi judul, kategori, isi, dan status publikasi.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Judul</label>
                            <input type="text" wire:model="title"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('title')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Kategori</label>
                            <input type="text" wire:model="category"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('category')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Ringkasan</label>
                        <textarea wire:model="excerpt" rows="3" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"></textarea>
                        @error('excerpt')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Isi Berita</label>
                        <div class="mt-2 overflow-hidden rounded-2xl border border-slate-300 bg-white">
                            <livewire:jodit-text-editor wire:model.live="content" identifier="news-content-editor"
                                :buttons="[
                                    'source',
                                    '|',
                                    'bold',
                                    'italic',
                                    'underline',
                                    'strikethrough',
                                    '|',
                                    'ul',
                                    'ol',
                                    '|',
                                    'outdent',
                                    'indent',
                                    '|',
                                    'font',
                                    'fontsize',
                                    'paragraph',
                                    'brush',
                                    '|',
                                    'link',
                                    'image',
                                    'table',
                                    '|',
                                    'align',
                                    'undo',
                                    'redo',
                                ]" theme="default" :key="'news-content-editor-' .
                                    ($editingId ?? 'create') .
                                    '-' .
                                    ($showFormModal ? 'open' : 'closed')" />
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Gunakan tombol gambar untuk menyisipkan foto ke isi
                            berita. File gambar akan diunggah ke storage publik.
                        </p>
                        @error('content')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Status</label>
                        <select wire:model="status"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                            <option value="draft">Draft</option>
                            <option value="published">Terbit</option>
                        </select>
                        @error('status')
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
                <h2 class="text-lg font-bold text-slate-800">Hapus Berita</h2>
                <p class="mt-2 text-sm text-slate-600">Berita yang dihapus tidak bisa dikembalikan.</p>

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
