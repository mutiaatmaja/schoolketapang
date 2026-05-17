<?php

use Livewire\Component;

new class extends Component {
    public array $sections = [['title' => 'Sejarah Sekolah', 'content' => 'Sekolah berdiri untuk menghadirkan pendidikan dasar berkualitas, berkarakter, dan ramah anak.'], ['title' => 'Visi Misi', 'content' => 'Membentuk siswa beriman, cerdas, disiplin, dan siap menghadapi masa depan.'], ['title' => 'Fasilitas', 'content' => 'Ruang kelas nyaman, perpustakaan, area olahraga, dan sarana belajar digital.'], ['title' => 'Prestasi', 'content' => 'Siswa aktif dalam lomba akademik, seni, olahraga, dan kegiatan karakter.']];
};
?>

<div class="space-y-4 p-4 md:p-8">
    <header class="space-y-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Profil Sekolah</p>
        <h1 class="text-2xl font-bold text-slate-800 md:text-3xl">Mengenal Sekolah Kami</h1>
        <p class="text-sm text-slate-600">Informasi inti sekolah untuk membantu orang tua memahami lingkungan belajar
            anak.</p>
    </header>

    <section class="space-y-3">
        @foreach ($sections as $section)
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm" wire:key="{{ $section['title'] }}">
                <h2 class="text-base font-semibold text-slate-800">{{ $section['title'] }}</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $section['content'] }}</p>
            </article>
        @endforeach
    </section>
</div>
