<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public array $stats = [['label' => 'Total Siswa', 'value' => 684, 'route' => 'admin.akademik.siswa'], ['label' => 'Total Kelas', 'value' => 18, 'route' => 'admin.akademik.kelas'], ['label' => 'Total Guru', 'value' => 42, 'route' => 'admin.akademik.guru']];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Akademik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Ringkasan Akademik</h1>
        <p class="mt-2 text-sm text-slate-600">Halaman ringkasan statistik siswa, kelas, dan guru (hardcode sementara).
        </p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Statistik Akademik</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($stats as $card)
                <article wire:key="akademik-stat-{{ $card['label'] }}" class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $card['value'] }}</p>
                    <a href="{{ route($card['route']) }}" wire:navigate
                        class="mt-3 inline-flex text-sm font-semibold text-sky-700 hover:underline">Buka detail</a>
                </article>
            @endforeach
        </div>
    </section>
</div>
