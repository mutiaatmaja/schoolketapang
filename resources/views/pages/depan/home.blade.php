<?php

use Livewire\Component;

new class extends Component {
    /**
     * @var array<int, array{title: string, subtitle: string, image: string}>
     */
    public array $heroSlides = [
        [
            'title' => 'Lingkungan Belajar Aman dan Menyenangkan',
            'subtitle' => 'Mendorong anak aktif, kreatif, dan percaya diri sejak dini.',
            'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1400&q=80',
        ],
        [
            'title' => 'Guru Profesional dan Peduli Karakter',
            'subtitle' => 'Pendekatan belajar yang hangat untuk perkembangan akademik dan akhlak.',
            'image' => 'https://images.unsplash.com/photo-1529390079861-591de354faf5?auto=format&fit=crop&w=1400&q=80',
        ],
        [
            'title' => 'Fasilitas Pendukung Pembelajaran Modern',
            'subtitle' => 'Ruang kelas nyaman, perpustakaan, dan kegiatan pengembangan bakat siswa.',
            'image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1400&q=80',
        ],
    ];

    public function openPpdb(): void
    {
        $this->dispatch('toast', type: 'info', message: 'Membuka halaman statistik SPMB...');
        $this->redirectRoute('ppdb.statistik', navigate: true);
    }
};
?>

<div class="space-y-8 p-4 md:p-8">
    <section x-data="{
        current: 0,
        slides: @js($heroSlides),
        autoplay: null,
        next() { this.current = (this.current + 1) % this.slides.length; },
        prev() { this.current = (this.current - 1 + this.slides.length) % this.slides.length; },
        start() { this.autoplay = setInterval(() => this.next(), 4500); },
        stop() { clearInterval(this.autoplay); }
    }" x-init="start()" @mouseenter="stop()" @mouseleave="start()"
        class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/30 via-transparent to-amber-400/20"></div>

        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="current === index" x-transition.opacity.duration.600ms class="relative min-h-[420px]">
                <img :src="slide.image" :alt="slide.title"
                    class="absolute inset-0 h-full w-full object-cover opacity-50">
                <div
                    class="relative flex min-h-[420px] items-end bg-gradient-to-t from-black/70 via-black/30 to-transparent p-5 md:p-8">
                    <div class="max-w-2xl space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-100">Website Resmi SD</p>
                        <h1 class="text-2xl font-bold leading-tight md:text-4xl" x-text="slide.title"></h1>
                        <p class="text-sm text-slate-100 md:text-base" x-text="slide.subtitle"></p>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                            <button type="button" wire:click="openPpdb" wire:loading.attr="disabled"
                                wire:target="openPpdb"
                                class="inline-flex items-center justify-center rounded-xl bg-cyan-400 px-4 py-3 text-sm font-semibold text-slate-900 disabled:opacity-60">
                                <span wire:loading.remove wire:target="openPpdb">Daftar SPMB Sekarang</span>
                                <span wire:loading wire:target="openPpdb">Memproses pendaftaran...</span>
                            </button>

                            <a href="{{ route('depan.profil') }}" wire:navigate
                                class="inline-flex items-center justify-center rounded-xl border border-white/40 px-4 py-3 text-sm font-semibold text-white">
                                Lihat Profil Sekolah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <button type="button" @click="prev()"
            class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/20 p-2 text-white backdrop-blur hover:bg-white/30"
            aria-label="Slide sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button type="button" @click="next()"
            class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/20 p-2 text-white backdrop-blur hover:bg-white/30"
            aria-label="Slide berikutnya">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-2">
            <template x-for="(slide, index) in slides" :key="`dot-${index}`">
                <button type="button" @click="current = index" class="h-2.5 w-2.5 rounded-full transition"
                    :class="current === index ? 'bg-cyan-300' : 'bg-white/40'" aria-label="Pilih slide"></button>
            </template>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-2">
        <article class="rounded-2xl border border-cyan-100 bg-gradient-to-br from-cyan-50 to-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-600">Jumlah Guru</p>
            <p class="mt-2 text-4xl font-extrabold text-cyan-700">42</p>
            <p class="mt-2 text-sm text-slate-600">Guru berpengalaman dan aktif dalam pengembangan pembelajaran siswa.
            </p>
        </article>
        <article class="rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-600">Jumlah Siswa</p>
            <p class="mt-2 text-4xl font-extrabold text-amber-600">680</p>
            <p class="mt-2 text-sm text-slate-600">Siswa aktif dari kelas 1 sampai kelas 6 dengan kegiatan belajar
                terstruktur.</p>
        </article>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
        <h2 class="text-xl font-bold text-slate-800">Visi dan Misi Sekolah</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <article class="rounded-xl bg-slate-50 p-4">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-cyan-700">Visi</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">Menjadi sekolah dasar unggul yang membentuk
                    generasi berkarakter, cerdas, dan berdaya saing global.</p>
            </article>
            <article class="rounded-xl bg-slate-50 p-4">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-cyan-700">Misi</h3>
                <ul class="mt-2 list-disc space-y-1 pl-4 text-sm leading-relaxed text-slate-600">
                    <li>Menyelenggarakan pembelajaran aktif, kreatif, dan menyenangkan.</li>
                    <li>Menanamkan nilai akhlak, disiplin, dan kepedulian sosial.</li>
                    <li>Mengembangkan potensi akademik dan non-akademik setiap siswa.</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
        <h2 class="text-xl font-bold text-slate-800">Info Sekolah</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            <div class="rounded-xl border border-slate-200 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">NPSN Sekolah</p>
                <p class="mt-1 text-sm font-semibold text-slate-800">20260201</p>
            </div>
            <div class="rounded-xl border border-slate-200 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Nama Sekolah</p>
                <p class="mt-1 text-sm font-semibold text-slate-800">SD Modern Ketapang</p>
            </div>
            <div class="rounded-xl border border-slate-200 p-4 md:col-span-2">
                <p class="text-xs uppercase tracking-wide text-slate-500">Alamat Sekolah</p>
                <p class="mt-1 text-sm font-semibold text-slate-800">Jl. Pendidikan No. 10, Kecamatan Ketapang, Kota
                    Pontianak, Kalimantan Barat</p>
            </div>
        </div>
    </section>

    <section class="rounded-2xl bg-slate-900 p-5 text-white shadow-sm md:p-6">
        <h2 class="text-xl font-bold">Kontak Sekolah</h2>
        <div class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
            <article class="rounded-xl border border-white/20 bg-white/5 p-4">
                <p class="text-xs uppercase tracking-wide text-cyan-200">Telepon / WhatsApp</p>
                <p class="mt-1 font-semibold">+62 812-3456-7890</p>
            </article>
            <article class="rounded-xl border border-white/20 bg-white/5 p-4">
                <p class="text-xs uppercase tracking-wide text-cyan-200">Email</p>
                <p class="mt-1 font-semibold">info@sdmodernketapang.sch.id</p>
            </article>
            <article class="rounded-xl border border-white/20 bg-white/5 p-4 sm:col-span-2">
                <p class="text-xs uppercase tracking-wide text-cyan-200">Jam Operasional</p>
                <p class="mt-1 font-semibold">Senin - Jumat, 07.00 - 15.30 WIB</p>
            </article>
        </div>
    </section>
</div>
