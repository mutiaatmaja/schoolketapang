<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public array $schoolInfo = [
        'NPSN' => '20123456',
        'Nama Sekolah' => 'SD Cerdas Ketapang',
        'Alamat' => 'Jl. Pendidikan No. 123, Ketapang, Kalimantan Barat',
        'No. Telepon' => '(0534) 123456',
        'Email' => 'info@sdcerdas.sch.id',
        'Website' => 'www.sdcerdas.sch.id',
        'Akreditasi' => 'A',
    ];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Informasi Sekolah</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola profil dasar sekolah yang ditampilkan di halaman publik.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Data Utama</h2>
        <dl class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
            @foreach ($schoolInfo as $label => $value)
                <div wire:key="school-info-{{ $label }}"
                    class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <dt class="text-xs uppercase tracking-wide text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Tautan Cepat</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.publik.visi-misi') }}" wire:navigate
                class="rounded-xl bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700">Visi Misi</a>
            <a href="{{ route('admin.publik.berita') }}" wire:navigate
                class="rounded-xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Berita</a>
            <a href="{{ route('admin.publik.prestasi') }}" wire:navigate
                class="rounded-xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Prestasi</a>
        </div>
    </section>
</div>
