<?php

use App\Models\SpmbRegistration;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::ppdb.app')] class extends Component {
    use WithPagination;

    public string $status = 'submitted';

    public string $title = 'Belum Validasi';

    public string $search = '';

    public function mount(string $status = 'submitted', string $title = 'Belum Validasi'): void
    {
        $allowed = ['submitted', 'verified', 'lulus', 'cadangan', 'ditolak'];

        if (!in_array($status, $allowed, true)) {
            abort(404);
        }

        $this->status = $status;
        $this->title = $title;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function getRowsProperty()
    {
        return SpmbRegistration::query()
            ->select(['registration_number', 'name', 'nik', 'status', 'validation_note', 'submitted_at', 'validated_at'])
            ->where('status', $this->status)
            ->when(trim($this->search) !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery
                        ->where('registration_number', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('validated_at')
            ->orderByDesc('submitted_at')
            ->paginate(10);
    }

    public function maskNik(?string $nik): string
    {
        if ($nik === null || $nik === '') {
            return '-';
        }

        if (strlen($nik) <= 3) {
            return str_repeat('*', strlen($nik));
        }

        return substr($nik, 0, -3) . '***';
    }

    public function maskName(string $name): string
    {
        $trimmed = trim($name);

        if ($trimmed === '') {
            return '-';
        }

        $parts = preg_split('/\s+/', $trimmed) ?: [];

        return collect($parts)
            ->map(function (string $part): string {
                $length = Str::length($part);

                if ($length <= 2) {
                    return Str::substr($part, 0, 1) . '*';
                }

                return Str::substr($part, 0, 1) . str_repeat('*', max($length - 2, 1)) . Str::substr($part, -1);
            })
            ->implode(' ');
    }

    public function publicValidationNote(?string $note): string
    {
        if ($note === null || trim($note) === '') {
            return '-';
        }

        return Str::limit(trim($note), 80);
    }
};
?>

<div class="space-y-6">
    <section class="rounded-[24px] border border-[#d8e4df] bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-lg font-semibold text-slate-900">Kategori {{ $title }}</h1>
            <span class="rounded-full bg-[#eef4f1] px-3 py-1 text-[11px] font-semibold text-[#1d4f45]">Read Only</span>
        </div>
        <p class="mt-2 text-xs leading-5 text-slate-500">Halaman publik kategori validasi. Data sensitif disamarkan,
            tanpa akses pengelolaan.</p>

        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('ppdb.statistik') }}" wire:navigate
                class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700">← Statistik</a>
            <a href="{{ route('ppdb.daftar') }}" wire:navigate
                class="rounded-xl bg-[#1d4f45] px-3 py-2 text-xs font-semibold text-white">Lanjut Daftar</a>
        </div>

        <div class="-mx-1 mt-4 flex gap-2 overflow-x-auto pb-1">
            @php
                $categoryTabs = [
                    ['route' => 'ppdb.kategori.belum-validasi', 'label' => 'Belum Validasi', 'status' => 'submitted'],
                    ['route' => 'ppdb.kategori.terverifikasi', 'label' => 'Terverifikasi', 'status' => 'verified'],
                    ['route' => 'ppdb.kategori.lulus', 'label' => 'Lulus', 'status' => 'lulus'],
                    ['route' => 'ppdb.kategori.cadangan', 'label' => 'Cadangan', 'status' => 'cadangan'],
                    ['route' => 'ppdb.kategori.ditolak', 'label' => 'Ditolak', 'status' => 'ditolak'],
                ];
            @endphp
            @foreach ($categoryTabs as $tab)
                <a href="{{ route($tab['route']) }}" wire:navigate @class([
                    'inline-flex shrink-0 items-center rounded-full px-4 py-1.5 text-xs font-semibold transition active:scale-95',
                    'bg-[#1d4f45] text-white' => $tab['status'] === $status,
                    'border border-slate-200 bg-slate-50 text-slate-600 hover:border-[#1d4f45] hover:text-[#1d4f45]' =>
                        $tab['status'] !== $status,
                ])>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="rounded-[24px] border border-[#eadfca] bg-[#fff9ef] p-5 shadow-sm">
        <div class="relative">
            <input type="text" wire:model.live.debounce.400ms="search"
                placeholder="Cari nomor peserta, nama, atau NIK"
                class="w-full rounded-2xl border border-[#dcccae] bg-white px-4 py-3 pr-16 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/10">
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-[#1d4f45]">Cari...</span>
        </div>

        <div class="mt-4 overflow-x-auto rounded-2xl border border-[#e9dcc5] bg-white">
            <table class="min-w-[760px] w-full text-left text-xs">
                <thead class="bg-[#f8f1e3] text-slate-600">
                    <tr>
                        <th class="px-3 py-2 font-semibold">Nomor Peserta</th>
                        <th class="px-3 py-2 font-semibold">Nama Siswa</th>
                        <th class="px-3 py-2 font-semibold">NIK (disamarkan)</th>
                        <th class="px-3 py-2 font-semibold">Waktu</th>
                        <th class="px-3 py-2 font-semibold">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->rows as $row)
                        <tr wire:key="{{ $row->registration_number }}" class="border-t border-slate-100">
                            <td class="px-3 py-2 font-semibold text-slate-700">{{ $row->registration_number }}</td>
                            <td class="px-3 py-2 text-slate-700">{{ $this->maskName($row->name) }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $this->maskNik($row->nik) }}</td>
                            <td class="px-3 py-2 text-slate-600">
                                {{ $row->validated_at?->format('d M Y H:i') ?? ($row->submitted_at?->format('d M Y H:i') ?? '-') }}
                            </td>
                            <td class="px-3 py-2 text-slate-600">
                                {{ $this->publicValidationNote($row->validation_note) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-5 text-center text-slate-500">Belum ada data pada kategori
                                ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $this->rows->links() }}
        </div>
    </section>
</div>
