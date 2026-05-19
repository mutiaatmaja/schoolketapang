<?php

use App\Models\SpmbRegistration;
use Livewire\Component;

new class extends Component {
    public function getStatsProperty(): array
    {
        $counts = SpmbRegistration::query()->selectRaw('COUNT(*) as total')->selectRaw("SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted")->selectRaw("SUM(CASE WHEN status = 'lulus' THEN 1 ELSE 0 END) as lulus")->selectRaw("SUM(CASE WHEN status = 'cadangan' THEN 1 ELSE 0 END) as cadangan")->selectRaw("SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak")->first();

        return [['label' => 'Total Pendaftar', 'value' => (int) ($counts?->total ?? 0), 'route' => 'admin.ppdb.pendaftar'], ['label' => 'Belum Validasi', 'value' => (int) ($counts?->submitted ?? 0), 'route' => 'admin.ppdb.belum-validasi'], ['label' => 'Peserta Lulus', 'value' => (int) ($counts?->lulus ?? 0), 'route' => 'admin.ppdb.lulus'], ['label' => 'Peserta Cadangan', 'value' => (int) ($counts?->cadangan ?? 0), 'route' => 'admin.ppdb.cadangan'], ['label' => 'Peserta Ditolak', 'value' => (int) ($counts?->ditolak ?? 0), 'route' => 'admin.ppdb.ditolak']];
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin SPMB</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Ringkasan SPMB</h1>
        <p class="mt-2 text-sm text-slate-600">Ringkasan data seleksi penerimaan murid baru berdasarkan data pendaftar
            saat ini.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Statistik Seleksi</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($this->stats as $card)
                <article wire:key="ppdb-stat-{{ $card['label'] }}" class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $card['value'] }}</p>
                    <a href="{{ route($card['route']) }}" wire:navigate
                        class="mt-3 inline-flex text-sm font-semibold text-sky-700 hover:underline">Buka detail</a>
                </article>
            @endforeach
        </div>
    </section>
</div>
