<?php

use App\Models\SpmbRegistration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::ppdb.app')] class extends Component {
    public string $searchKeyword = '';

    public bool $searchRateLimited = false;

    public int $searchRateLimitedSeconds = 0;

    public function getStatsProperty(): array
    {
        $counts = SpmbRegistration::query()->selectRaw('COUNT(*) as total')->selectRaw("SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted")->selectRaw("SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as verified")->selectRaw("SUM(CASE WHEN status = 'lulus' THEN 1 ELSE 0 END) as lulus")->selectRaw("SUM(CASE WHEN status = 'cadangan' THEN 1 ELSE 0 END) as cadangan")->selectRaw("SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak")->first();

        return [['label' => 'Total Pendaftar', 'value' => (int) ($counts?->total ?? 0), 'route' => null], ['label' => 'Belum Validasi', 'value' => (int) ($counts?->submitted ?? 0), 'route' => 'ppdb.kategori.belum-validasi'], ['label' => 'Terverifikasi', 'value' => (int) ($counts?->verified ?? 0), 'route' => 'ppdb.kategori.terverifikasi'], ['label' => 'Lulus', 'value' => (int) ($counts?->lulus ?? 0), 'route' => 'ppdb.kategori.lulus'], ['label' => 'Cadangan', 'value' => (int) ($counts?->cadangan ?? 0), 'route' => 'ppdb.kategori.cadangan'], ['label' => 'Ditolak', 'value' => (int) ($counts?->ditolak ?? 0), 'route' => 'ppdb.kategori.ditolak']];
    }

    public function getSearchResultsProperty(): Collection
    {
        $keyword = trim($this->searchKeyword);

        if ($keyword === '') {
            return collect();
        }

        if ($this->isSearchRateLimited()) {
            return collect();
        }

        return SpmbRegistration::query()
            ->select(['registration_number', 'name', 'nik', 'status', 'validation_note', 'submitted_at', 'validated_at'])
            ->where(function ($query) use ($keyword): void {
                $query->where('registration_number', 'like', '%' . $keyword . '%');

                if (preg_match('/^[0-9]+$/', $keyword) === 1) {
                    $query->orWhere('nik', 'like', '%' . $keyword . '%');
                }
            })
            ->orderByDesc('validated_at')
            ->orderByDesc('submitted_at')
            ->limit(20)
            ->get();
    }

    public function updatedSearchKeyword(): void
    {
        if (trim($this->searchKeyword) === '') {
            $this->searchRateLimited = false;
            $this->searchRateLimitedSeconds = 0;

            return;
        }

        if ($this->isSearchRateLimited()) {
            return;
        }

        RateLimiter::hit($this->searchThrottleKey(), 60);
    }

    private function searchThrottleKey(): string
    {
        return 'public-ppdb-search:' . sha1((string) request()->ip());
    }

    private function isSearchRateLimited(): bool
    {
        $key = $this->searchThrottleKey();
        $maxAttempts = 20;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $this->searchRateLimited = true;
            $this->searchRateLimitedSeconds = RateLimiter::availableIn($key);

            return true;
        }

        $this->searchRateLimited = false;
        $this->searchRateLimitedSeconds = 0;

        return false;
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

    public function statusLabel(string $status): string
    {
        return match ($status) {
            'submitted' => 'Belum Validasi',
            'verified' => 'Terverifikasi',
            'lulus' => 'Lulus',
            'cadangan' => 'Cadangan',
            'ditolak' => 'Ditolak',
            default => Str::headline($status),
        };
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
            <h1 class="text-lg font-semibold text-slate-900">Statistik dan Cek Status SPMB</h1>
            <span class="rounded-full bg-[#eef4f1] px-3 py-1 text-[11px] font-semibold text-[#1d4f45]">Read Only</span>
        </div>
        <p class="mt-2 text-xs leading-5 text-slate-500">Halaman publik untuk melihat statistik dan status pendaftaran.
            Data sensitif disamarkan dan tidak tersedia aksi pengelolaan.</p>

        <div class="mt-4 grid grid-cols-2 gap-3">
            @foreach ($this->stats as $stat)
                <article wire:key="stat-{{ $stat['label'] }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-[11px] uppercase tracking-wide text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                    @if ($stat['route'])
                        <a href="{{ route($stat['route']) }}" wire:navigate
                            class="mt-2 inline-flex text-xs font-semibold text-[#1d4f45] hover:underline">Lihat
                            daftar</a>
                    @endif
                </article>
            @endforeach
        </div>
    </section>

    <section class="rounded-[24px] border border-[#eadfca] bg-[#fff9ef] p-5 shadow-sm">
        <h2 class="text-base font-semibold text-slate-900">Cek Status Pendaftaran</h2>
        <p class="mt-2 text-xs leading-5 text-slate-600">Masukkan NIK atau Nomor Peserta SPMB untuk memeriksa status
            terbaru anak.</p>

        <div class="relative mt-4">
            <input type="text" wire:model.live.debounce.400ms="searchKeyword"
                placeholder="Contoh: 3201xxxxxxxxxxxx atau SPMB-2026-00001"
                class="w-full rounded-2xl border border-[#dcccae] bg-white px-4 py-3 pr-16 text-sm focus:border-[#1d4f45] focus:outline-none focus:ring-2 focus:ring-[#1d4f45]/10">
            <span wire:loading wire:target="searchKeyword"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-[#1d4f45]">Cari...</span>
        </div>

        @if ($searchRateLimited)
            <p class="mt-3 rounded-xl border border-amber-300 bg-amber-50 px-3 py-2 text-xs font-medium text-amber-800">
                Pencarian dibatasi sementara. Coba lagi dalam {{ $searchRateLimitedSeconds }} detik.
            </p>
        @endif

        @if (trim($searchKeyword) !== '')
            <div class="mt-4 overflow-x-auto rounded-2xl border border-[#e9dcc5] bg-white">
                <table class="min-w-[720px] w-full text-left text-xs">
                    <thead class="bg-[#f8f1e3] text-slate-600">
                        <tr>
                            <th class="px-3 py-2 font-semibold">Nomor Peserta</th>
                            <th class="px-3 py-2 font-semibold">Nama Siswa</th>
                            <th class="px-3 py-2 font-semibold">NIK (disamarkan)</th>
                            <th class="px-3 py-2 font-semibold">Status</th>
                            <th class="px-3 py-2 font-semibold">Update</th>
                            <th class="px-3 py-2 font-semibold">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->searchResults as $result)
                            <tr wire:key="search-{{ $result->registration_number }}" class="border-t border-slate-100">
                                <td class="px-3 py-2 font-semibold text-slate-700">{{ $result->registration_number }}
                                </td>
                                <td class="px-3 py-2 text-slate-700">{{ $this->maskName($result->name) }}</td>
                                <td class="px-3 py-2 text-slate-600">{{ $this->maskNik($result->nik) }}</td>
                                <td class="px-3 py-2 text-slate-700">{{ $this->statusLabel($result->status) }}</td>
                                <td class="px-3 py-2 text-slate-600">
                                    {{ $result->validated_at?->format('d M Y H:i') ?? ($result->submitted_at?->format('d M Y H:i') ?? '-') }}
                                </td>
                                <td class="px-3 py-2 text-slate-600">
                                    {{ $this->publicValidationNote($result->validation_note) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-5 text-center text-slate-500">Data tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="rounded-[24px] border border-[#d8e4df] bg-white p-5 shadow-sm">
        <h2 class="text-base font-semibold text-slate-900">Lihat Daftar per Kategori</h2>
        <p class="mt-2 text-xs leading-5 text-slate-500">Ketuk kategori untuk membuka tabel peserta di halaman terpisah.
            Geser ke kanan jika tab tidak muat di layar.</p>

        <div class="mt-4 -mx-1 flex gap-2 overflow-x-auto pb-1 scrollbar-none">
            @php
                $tabs = [
                    ['route' => 'ppdb.kategori.belum-validasi', 'label' => 'Belum Validasi', 'icon' => '⏳'],
                    ['route' => 'ppdb.kategori.terverifikasi', 'label' => 'Terverifikasi', 'icon' => '✅'],
                    ['route' => 'ppdb.kategori.lulus', 'label' => 'Lulus', 'icon' => '🎉'],
                    ['route' => 'ppdb.kategori.cadangan', 'label' => 'Cadangan', 'icon' => '🔁'],
                    ['route' => 'ppdb.kategori.ditolak', 'label' => 'Ditolak', 'icon' => '❌'],
                ];
            @endphp
            @foreach ($tabs as $tab)
                <a href="{{ route($tab['route']) }}" wire:navigate
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-[#1d4f45] hover:bg-[#eef4f1] hover:text-[#1d4f45] active:scale-95">
                    <span>{{ $tab['icon'] }}</span>
                    <span>{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="rounded-[28px] bg-[#1d4f45] px-5 py-6 text-white shadow-lg shadow-[#1d4f45]/20">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-white/75">SPMB SD Ketapang</p>
        <h2 class="mt-3 text-[1.9rem] font-bold leading-tight">Lanjutkan ke Formulir Pendaftaran</h2>
        <p class="mt-3 text-sm leading-6 text-white/80">Jika data dan status sudah Anda cek, lanjutkan ke formulir
            pendaftaran.</p>
        <div class="mt-5">
            <a href="{{ route('ppdb.daftar') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-2xl bg-[#f6c453] px-4 py-3 text-sm font-semibold text-[#18352f] transition hover:bg-[#f0ba3a]">
                Mulai Pendaftaran
            </a>
        </div>
    </section>
</div>
