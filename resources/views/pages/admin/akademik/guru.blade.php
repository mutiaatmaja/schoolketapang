<?php

use App\Exports\TeachersTemplateExport;
use App\Imports\TeachersImport;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

new class extends Component {
    use WithFileUploads;

    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public bool $showImportModal = false;

    public string $name = '';

    public string $nuptk = '';

    public string $nip = '';

    public string $nik = '';

    public string $gender = 'Laki-laki';

    public string $birthPlace = '';

    public string $birthDate = '';

    public string $employmentStatus = '';

    public string $religion = 'Islam';

    public string $address = '';

    public string $phone = '';

    public string $email = '';

    public $importFile = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nuptk' => ['nullable', 'string', 'max:30', Rule::unique('teachers', 'nuptk')->ignore($this->editingId)],
            'nip' => ['nullable', 'string', 'max:30', Rule::unique('teachers', 'nip')->ignore($this->editingId)],
            'nik' => ['required', 'string', 'max:30', Rule::unique('teachers', 'nik')->ignore($this->editingId)],
            'gender' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'birthPlace' => ['required', 'string', 'max:100'],
            'birthDate' => ['required', 'date'],
            'employmentStatus' => ['required', 'string', 'max:100'],
            'religion' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', Rule::unique('teachers', 'email')->ignore($this->editingId)],
        ];
    }

    public function getTeachersProperty(): Collection
    {
        return Teacher::query()
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery
                        ->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nuptk', 'like', '%' . $this->search . '%')
                        ->orWhere('nip', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('employment_status', 'like', '%' . $this->search . '%');
                });
            })
            ->with('homeroomClass:id,teacher_id,name')
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
        $teacher = Teacher::query()->findOrFail($id);

        $this->editingId = $teacher->id;
        $this->name = $teacher->name;
        $this->nuptk = $teacher->nuptk ?? '';
        $this->nip = $teacher->nip ?? '';
        $this->nik = $teacher->nik;
        $this->gender = $teacher->gender;
        $this->birthPlace = $teacher->birth_place;
        $this->birthDate = $teacher->birth_date?->format('Y-m-d') ?? '';
        $this->employmentStatus = $teacher->employment_status;
        $this->religion = $teacher->religion;
        $this->address = $teacher->address;
        $this->phone = $teacher->phone;
        $this->email = $teacher->email;
        $this->showFormModal = true;
    }

    public function openImport(): void
    {
        $this->resetImportForm();
        $this->showImportModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        Teacher::query()->updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $validated['name'],
                'nuptk' => $validated['nuptk'] ?: null,
                'nip' => $validated['nip'] ?: null,
                'nik' => $validated['nik'],
                'gender' => $validated['gender'],
                'birth_place' => $validated['birthPlace'],
                'birth_date' => $validated['birthDate'],
                'employment_status' => $validated['employmentStatus'],
                'religion' => $validated['religion'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
            ],
        );

        $message = $this->editingId ? 'Data guru berhasil diperbarui.' : 'Data guru berhasil ditambahkan.';

        $this->closeModal();
        $this->dispatch('toast', type: 'success', message: $message);
    }

    public function importTeachers(): void
    {
        $this->validate([
            'importFile' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        try {
            $import = new TeachersImport();
            Excel::import($import, $this->importFile);

            if ($import->processedRows() === 0) {
                throw ValidationException::withMessages([
                    'importFile' => 'Tidak ada baris guru yang berhasil diproses. Gunakan template import dan pastikan data terisi.',
                ]);
            }
        } catch (ValidationException $exception) {
            $this->resetValidation();

            foreach ($exception->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            $this->dispatch('toast', type: 'error', message: collect($exception->errors())->flatten()->first() ?? 'Import guru gagal.');

            return;
        } catch (\Throwable $throwable) {
            $message = 'Terjadi kesalahan saat mengimpor data guru.';

            $this->addError('importFile', $message);
            $this->dispatch('toast', type: 'error', message: $message);

            report($throwable);

            return;
        }

        $this->resetImportForm();
        $this->showImportModal = false;
        $this->dispatch('toast', type: 'success', message: 'Import guru berhasil. ' . $import->processedRows() . ' baris diproses.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new TeachersTemplateExport(), 'template-import-guru.xlsx');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        Teacher::query()->findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->showDeleteModal = false;
        $this->dispatch('toast', type: 'success', message: 'Data guru berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->resetForm();
        $this->resetImportForm();
        $this->showFormModal = false;
        $this->showDeleteModal = false;
        $this->showImportModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->name = '';
        $this->nuptk = '';
        $this->nip = '';
        $this->nik = '';
        $this->gender = 'Laki-laki';
        $this->birthPlace = '';
        $this->birthDate = '';
        $this->employmentStatus = '';
        $this->religion = 'Islam';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
    }

    private function resetImportForm(): void
    {
        $this->resetValidation();
        $this->importFile = null;
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Akademik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Data Guru</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola profil guru dan lakukan import Excel saat menerima data massal.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Guru</h2>
                <p class="mt-1 text-sm text-slate-500">Simpan data identitas, kontak, dan status kepegawaian guru.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="button" wire:click="openImport" wire:loading.attr="disabled" wire:target="openImport"
                    class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                    <span wire:loading.remove wire:target="openImport">Import Excel</span>
                    <span wire:loading wire:target="openImport">Membuka...</span>
                </button>
                <button type="button" wire:click="openCreate" wire:loading.attr="disabled" wire:target="openCreate"
                    class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                    <span wire:loading.remove wire:target="openCreate">Tambah Guru</span>
                    <span wire:loading wire:target="openCreate">Membuka...</span>
                </button>
            </div>
        </div>

        <div class="relative mt-4">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari nama, NIK, email, atau status"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-24 text-sm" />
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Mencari...</span>
        </div>

        <div class="relative mt-4 overflow-hidden rounded-2xl border border-slate-200">
            <div wire:loading.flex wire:target="search,save,delete,openEdit,confirmDelete,importTeachers"
                class="absolute inset-0 z-10 hidden items-center justify-center bg-white/80 text-sm font-semibold text-slate-600">
                Memproses data guru...
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Nama</th>
                            <th class="px-4 py-3 font-medium">Identitas</th>
                            <th class="px-4 py-3 font-medium">Kepegawaian</th>
                            <th class="px-4 py-3 font-medium">Kontak</th>
                            <th class="px-4 py-3 font-medium">Wali Kelas</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->teachers as $teacher)
                            <tr wire:key="teacher-{{ $teacher->id }}" class="border-t border-slate-100 align-top">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-800">{{ $teacher->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $teacher->gender }} ·
                                        {{ $teacher->religion }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p>NIK: {{ $teacher->nik }}</p>
                                    <p class="mt-1">NIP: {{ $teacher->nip ?: '-' }}</p>
                                    <p class="mt-1">NUPTK: {{ $teacher->nuptk ?: '-' }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $teacher->employment_status }}</td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p>{{ $teacher->phone }}</p>
                                    <p class="mt-1">{{ $teacher->email }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $teacher->homeroomClass?->name ? 'Kelas ' . $teacher->homeroomClass->name : 'Belum ditentukan' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="openEdit({{ $teacher->id }})"
                                            wire:loading.attr="disabled" wire:target="openEdit({{ $teacher->id }})"
                                            class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="openEdit({{ $teacher->id }})">Edit</span>
                                            <span wire:loading
                                                wire:target="openEdit({{ $teacher->id }})">Membuka...</span>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $teacher->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDelete({{ $teacher->id }})"
                                            class="rounded-lg bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="confirmDelete({{ $teacher->id }})">Hapus</span>
                                            <span wire:loading
                                                wire:target="confirmDelete({{ $teacher->id }})">Menyiapkan...</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada data guru.
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
            <div class="w-full max-w-4xl max-h-[calc(100vh-3rem)] overflow-y-auto rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">{{ $editingId ? 'Edit Guru' : 'Tambah Guru' }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">Lengkapi data identitas guru sesuai dokumen resmi
                            sekolah.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <form wire:submit="save" class="mt-6 space-y-5">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nama Guru</label>
                            <input type="text" wire:model="name"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('name')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">NIK</label>
                            <input type="text" wire:model="nik"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('nik')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">NUPTK</label>
                            <input type="text" wire:model="nuptk"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('nuptk')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">NIP</label>
                            <input type="text" wire:model="nip"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('nip')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Jenis Kelamin</label>
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
                            <input type="text" wire:model="religion"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('religion')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Tempat Lahir</label>
                            <input type="text" wire:model="birthPlace"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('birthPlace')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Tanggal Lahir</label>
                            <input type="date" wire:model="birthDate"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('birthDate')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Status Kepegawaian</label>
                            <input type="text" wire:model="employmentStatus"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"
                                placeholder="Tetap / Honorer / Kontrak" />
                            @error('employmentStatus')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">HP</label>
                            <input type="text" wire:model="phone"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('phone')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" wire:model="email"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" />
                            @error('email')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Alamat</label>
                            <textarea wire:model="address" rows="4"
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm"></textarea>
                            @error('address')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
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

    @if ($showImportModal)
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto bg-slate-900/50 px-4 py-6 sm:items-center sm:py-10"
            style="z-index: 120;">
            <div class="w-full max-w-xl rounded-3xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Import Guru</h2>
                        <p class="mt-1 text-sm text-slate-500">Unduh template dulu, lalu upload file Excel dengan
                            heading yang sama.</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600">Tutup</button>
                </div>

                <div
                    class="mt-5 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-600">
                    Format tanggal lahir gunakan <span class="font-semibold text-slate-800">YYYY-MM-DD</span>.
                </div>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <button type="button" wire:click="downloadTemplate" wire:loading.attr="disabled"
                        wire:target="downloadTemplate"
                        class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                        <span wire:loading.remove wire:target="downloadTemplate">Unduh Template</span>
                        <span wire:loading wire:target="downloadTemplate">Menyiapkan...</span>
                    </button>
                </div>

                <form wire:submit="importTeachers" class="mt-5 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">File Excel</label>
                        <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv"
                            class="mt-2 block w-full text-sm text-slate-600" />
                        <p wire:loading wire:target="importFile" class="mt-1 text-xs text-slate-500">Mengunggah
                            file...</p>
                        @error('importFile')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="importTeachers"
                            class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                            <span wire:loading.remove wire:target="importTeachers">Import</span>
                            <span wire:loading wire:target="importTeachers">Mengimpor...</span>
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
                <h2 class="text-lg font-bold text-slate-800">Hapus Guru</h2>
                <p class="mt-2 text-sm text-slate-600">Data guru yang dihapus tidak bisa dikembalikan.</p>

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
