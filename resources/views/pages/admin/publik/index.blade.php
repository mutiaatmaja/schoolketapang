<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public array $schoolInfo = [
        'NPSN' => '20123456',
        'Nama Sekolah' => 'SD Cerdas Ketapang',
        'Alamat' => 'Jl. Pendidikan No. 123, Ketapang',
        'No. Telepon' => '(0534) 123456',
        'Email' => 'info@sdcerdas.sch.id',
    ];

    public array $visiMisi = [
        'visi' => 'Menjadi sekolah dasar unggul yang membentuk murid berkarakter, berprestasi, dan siap menghadapi masa depan.',
        'misi' => ['Menyelenggarakan pembelajaran aktif, kreatif, dan menyenangkan.', 'Menanamkan nilai religius, disiplin, dan kepedulian sosial.', 'Mengembangkan potensi akademik dan non-akademik murid secara seimbang.'],
    ];

    public array $summary = [['label' => 'Jumlah Berita', 'count' => 24, 'route' => 'admin.publik.berita'], ['label' => 'Jumlah Prestasi', 'count' => 18, 'route' => 'admin.publik.prestasi'], ['label' => 'Jumlah Pesan', 'count' => 31, 'route' => 'admin.publik.info-sekolah']];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Kelola Konten Publik Sekolah</h1>
        <p class="mt-2 text-sm text-slate-600">Informasi sekolah, visi misi, dan ringkasan konten publik dalam satu
            halaman.</p>
    </header>
    <section id="ringkasan" class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
        <h2 class="text-lg font-bold text-slate-800">Ringkasan Konten Publik</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($summary as $card)
                <article wire:key="summary-{{ $card['label'] }}" class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $card['count'] }}</p>
                    <a href="{{ route($card['route']) }}" wire:navigate
                        class="mt-3 inline-flex text-sm font-semibold text-sky-700 hover:underline">
                        Buka tautan
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <section id="info-sekolah" class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-slate-800">Informasi Sekolah</h2>
            <a href="{{ route('admin.publik.info-sekolah') }}" wire:navigate
                class="text-sm font-semibold text-sky-700 hover:underline">Tautan Halaman</a>
        </div>
        <dl class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
            @foreach ($schoolInfo as $label => $value)
                <div wire:key="info-{{ $label }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <dt class="text-xs uppercase tracking-wide text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </section>

    <section id="visi-misi" class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-slate-800">Visi & Misi</h2>
            <a href="{{ route('admin.publik.visi-misi') }}" wire:navigate
                class="text-sm font-semibold text-sky-700 hover:underline">Tautan Halaman</a>
        </div>

        <article class="mt-4 rounded-2xl bg-sky-50 p-4 border border-sky-100">
            <h3 class="text-sm font-bold text-sky-800">Visi</h3>
            <p class="mt-2 text-sm text-slate-700">{{ $visiMisi['visi'] }}</p>
        </article>

        <article class="mt-4 rounded-2xl bg-amber-50 p-4 border border-amber-100">
            <h3 class="text-sm font-bold text-amber-800">Misi</h3>
            <ul class="mt-2 space-y-2 text-sm text-slate-700 list-disc pl-5">
                @foreach ($visiMisi['misi'] as $item)
                    <li wire:key="misi-{{ md5($item) }}">{{ $item }}</li>
                @endforeach
            </ul>
        </article>
    </section>


</div>
