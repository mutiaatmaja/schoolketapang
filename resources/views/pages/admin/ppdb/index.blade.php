<?php

use Livewire\Component;

new class extends Component {
    public array $stats = [['label' => 'Total Pendaftar', 'value' => 186, 'route' => 'admin.ppdb.pendaftar'], ['label' => 'Belum Validasi', 'value' => 34, 'route' => 'admin.ppdb.belum-validasi'], ['label' => 'Peserta Lulus', 'value' => 102, 'route' => 'admin.ppdb.lulus'], ['label' => 'Peserta Cadangan', 'value' => 26, 'route' => 'admin.ppdb.cadangan'], ['label' => 'Peserta Ditolak', 'value' => 24, 'route' => 'admin.ppdb.ditolak']];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin SPMB</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Ringkasan SPMB</h1>
        <p class="mt-2 text-sm text-slate-600">Ringkasan data seleksi penerimaan murid baru (hardcode sementara).</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Statistik Seleksi</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($stats as $card)
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
