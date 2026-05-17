<?php

use Livewire\Component;

new class extends Component {
    public array $stats = [
        'total_pendaftar' => 128,
        'menunggu_verifikasi' => 32,
        'lulus' => 74,
        'cadangan' => 12,
    ];

    public function refreshStats(): void
    {
        $this->dispatch('toast', type: 'success', message: 'Statistik dashboard berhasil diperbarui.');
    }
};
?>

<div class="space-y-5 p-4 md:p-8">
    <header class="flex items-start justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Dashboard</p>
            <h1 class="text-2xl font-bold text-slate-800">Ringkasan PPDB</h1>
        </div>
        <button type="button" wire:click="refreshStats" wire:loading.attr="disabled" wire:target="refreshStats"
            class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
            <span wire:loading.remove wire:target="refreshStats">Refresh Data</span>
            <span wire:loading wire:target="refreshStats">Memuat statistik...</span>
        </button>
    </header>

    <section class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs text-slate-500">Total Pendaftar</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $stats['total_pendaftar'] }}</p>
        </article>
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs text-slate-500">Menunggu Verifikasi</p>
            <p class="mt-2 text-2xl font-bold text-amber-600">{{ $stats['menunggu_verifikasi'] }}</p>
        </article>
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs text-slate-500">Lulus</p>
            <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $stats['lulus'] }}</p>
        </article>
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs text-slate-500">Cadangan</p>
            <p class="mt-2 text-2xl font-bold text-blue-600">{{ $stats['cadangan'] }}</p>
        </article>
    </section>
</div>
