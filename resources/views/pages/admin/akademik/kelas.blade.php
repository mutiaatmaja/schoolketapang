<?php

use App\Models\SchoolClass;
use App\Models\Teacher;
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

    public ?int $teacherId = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::in(['1', '2', '3', '4', '5', '6']), Rule::unique('school_classes', 'name')->ignore($this->editingId)],
            'teacherId' => ['nullable', 'integer', 'exists:teachers,id', Rule::unique('school_classes', 'teacher_id')->ignore($this->editingId)],
        ];
    }

    public function getClassesProperty(): Collection
    {
        return SchoolClass::query()
            ->with(['homeroomTeacher:id,name'])
            ->withCount('students')
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery->where('name', 'like', '%' . $this->search . '%')->orWhereHas('homeroomTeacher', function ($teacherQuery): void {
                        $teacherQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->ordered()
            ->get();
    }

    public function getTeachersProperty(): Collection
    {
        return Teacher::query()->ordered()->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $class = SchoolClass::query()->findOrFail($id);

        $this->editingId = $class->id;
        $this->name = $class->name;
        $this->teacherId = $class->teacher_id;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        SchoolClass::query()->updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $validated['name'],
                'teacher_id' => $validated['teacherId'],
            ],
        );

        $message = $this->editingId ? 'Data kelas berhasil diperbarui.' : 'Data kelas berhasil ditambahkan.';

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
        $class = SchoolClass::query()->withCount('students')->findOrFail($this->deletingId);

        if ($class->students_count > 0) {
            $this->showDeleteModal = false;
            $this->deletingId = null;
            $this->dispatch('toast', type: 'error', message: 'Kelas tidak dapat dihapus karena masih memiliki siswa.');

            return;
        }

        $class->delete();

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('toast', type: 'success', message: 'Data kelas berhasil dihapus.');
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
        $this->teacherId = null;
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Akademik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Data Kelas</h1>
        <p class="mt-2 text-sm text-slate-600">Atur nama kelas 1-6 dan hubungkan dengan wali kelas.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Kelas</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih wali kelas dari data guru yang sudah tersimpan.</p>
            </div>
            <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                <span wire:loading.remove wire:target="openCreate">Tambah Kelas</span>
                <span wire:loading wire:target="openCreate">Membuka...</span>
            </button>
        </div>

        <div class="relative mt-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kelas atau wali kelas"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-24 text-sm" />
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Mencari...</span>
        </div>

        <div class="relative mt-4 overflow-hidden rounded-2xl border border-slate-200">
            <div wire:loading.flex wire:target="search,save,delete,openEdit,confirmDelete"
                class="absolute inset-0 z-10 hidden items-center justify-center bg-white/80 text-sm font-semibold text-slate-600">
                Memproses data kelas...
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Kelas</th>
                            <th class="px-4 py-3 font-medium">Wali Kelas</th>
                            <th class="px-4 py-3 font-medium">Jumlah Siswa</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->classes as $class)
                            <tr wire:key="class-{{ $class->id }}" class="border-t border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-800">Kelas {{ $class->name }}</td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $class->homeroomTeacher?->name ?? 'Belum ditentukan' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $class->students_count }} siswa</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="openEdit({{ $class->id }})"
                                            wire:loading.attr="disabled" wire:target="openEdit({{ $class->id }})"
                                            class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="openEdit({{ $class->id }})">Edit</span>
                                            <span wire:loading
                                                wire:target="openEdit({{ $class->id }})">Membuka...</span>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $class->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDelete({{ $class->id }})"
                                            class="rounded-lg bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="confirmDelete({{ $class->id }})">Hapus</span>
                                            <span wire:loading
                                                wire:target="confirmDelete({{ $class->id }})">Menyiapkan...</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada data kelas.
                                </td>
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
            <div class="w-full max-w-lg max-h-[calc(100vh-3rem)] overflow-y-auto rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">{{ $editingId ? 'Edit Kelas' : 'Tambah Kelas' }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">Nama kelas dibatasi 1 sampai 6 sesuai kebutuhan sekolah
                            dasar.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Nama Kelas</label>
                        <select wire:model="name"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                            <option value="">Pilih kelas</option>
                            @foreach (['1', '2', '3', '4', '5', '6'] as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Wali Kelas</label>
                        <select wire:model="teacherId"
                            class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                            <option value="">Belum ditentukan</option>
                            @foreach ($this->teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                        @error('teacherId')
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
                <h2 class="text-lg font-bold text-slate-800">Hapus Kelas</h2>
                <p class="mt-2 text-sm text-slate-600">Kelas yang masih punya siswa tidak bisa dihapus.</p>

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
