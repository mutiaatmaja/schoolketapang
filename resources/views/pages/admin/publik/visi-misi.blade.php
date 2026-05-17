<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public string $visi = 'Menjadi sekolah dasar unggul yang membentuk murid berkarakter, berprestasi, dan siap menghadapi masa depan.';

    public array $misi = ['Menyelenggarakan pembelajaran aktif, kreatif, dan menyenangkan.', 'Menanamkan nilai religius, disiplin, dan kepedulian sosial.', 'Mengembangkan potensi akademik dan non-akademik murid secara seimbang.', 'Membangun kolaborasi antara sekolah, orang tua, dan masyarakat.'];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Visi & Misi</h1>
        <p class="mt-2 text-sm text-slate-600">Atur narasi visi dan misi yang ditampilkan di website sekolah.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Visi</h2>
        <div class="mt-4 rounded-2xl border border-sky-100 bg-sky-50 p-4 text-sm text-slate-700">
            {{ $visi }}
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Misi</h2>
        <ul class="mt-4 list-disc space-y-2 pl-5 text-sm text-slate-700">
            @foreach ($misi as $item)
                <li wire:key="visi-misi-item-{{ md5($item) }}">{{ $item }}</li>
            @endforeach
        </ul>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Tautan Cepat</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.publik.info-sekolah') }}" wire:navigate
                class="rounded-xl bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700">Info Sekolah</a>
            <a href="{{ route('admin.publik.berita') }}" wire:navigate
                class="rounded-xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Berita</a>
            <a href="{{ route('admin.publik.prestasi') }}" wire:navigate
                class="rounded-xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Prestasi</a>
        </div>
    </section>
</div>
