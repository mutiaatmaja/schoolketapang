<!DOCTYPE html>

<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600;700&amp;family=Nunito+Sans:wght@400;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed": "#ffdf9f",
                        "inverse-on-surface": "#eff1f3",
                        "surface-container-low": "#f2f4f6",
                        "tertiary-fixed-dim": "#ffb59a",
                        "on-tertiary-fixed-variant": "#802a00",
                        "on-primary-fixed": "#001453",
                        "on-error": "#ffffff",
                        "on-primary-container": "#a8b8ff",
                        "on-surface": "#191c1e",
                        "tertiary-container": "#872d00",
                        "primary-fixed": "#dde1ff",
                        "surface-variant": "#e0e3e5",
                        "on-error-container": "#93000a",
                        "surface-container-highest": "#e0e3e5",
                        "on-primary-fixed-variant": "#173bab",
                        "outline-variant": "#c4c5d5",
                        "tertiary-fixed": "#ffdbce",
                        "primary-fixed-dim": "#b8c4ff",
                        "tertiary": "#611e00",
                        "outline": "#757684",
                        "secondary-container": "#ffc329",
                        "surface-container": "#eceef0",
                        "primary-container": "#1e40af",
                        "surface-tint": "#3755c3",
                        "on-tertiary": "#ffffff",
                        "secondary": "#795900",
                        "surface-dim": "#d8dadc",
                        "background": "#f7f9fb",
                        "inverse-surface": "#2d3133",
                        "on-background": "#191c1e",
                        "on-secondary-fixed": "#261a00",
                        "on-tertiary-container": "#ffa583",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-secondary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "secondary-fixed-dim": "#f9bd22",
                        "inverse-primary": "#b8c4ff",
                        "surface-bright": "#f7f9fb",
                        "surface-container-high": "#e6e8ea",
                        "primary": "#00288e",
                        "surface": "#f7f9fb",
                        "on-primary": "#ffffff",
                        "on-surface-variant": "#444653",
                        "on-secondary-container": "#6f5100",
                        "on-secondary-fixed-variant": "#5c4300",
                        "on-tertiary-fixed": "#380d00"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "3xl": "1.5rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "container-max": "1280px",
                        "section-padding-desktop": "80px",
                        "section-padding-mobile": "40px",
                        "gutter": "24px",
                        "base": "8px"
                    },
                    "fontFamily": {
                        "headline-lg": ["Quicksand"],
                        "headline-xl": ["Quicksand"],
                        "label-md": ["Nunito Sans"],
                        "body-md": ["Nunito Sans"],
                        "body-lg": ["Nunito Sans"],
                        "stats-number": ["Quicksand"],
                        "headline-md": ["Quicksand"],
                        "headline-xl-mobile": ["Quicksand"]
                    },
                    "fontSize": {
                        "headline-lg": ["32px", {
                            "lineHeight": "1.3",
                            "fontWeight": "600"
                        }],
                        "headline-xl": ["48px", {
                            "lineHeight": "1.2",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "label-md": ["14px", {
                            "lineHeight": "1.2",
                            "letterSpacing": "0.05em",
                            "fontWeight": "700"
                        }],
                        "body-md": ["16px", {
                            "lineHeight": "1.6",
                            "fontWeight": "400"
                        }],
                        "body-lg": ["18px", {
                            "lineHeight": "1.6",
                            "fontWeight": "400"
                        }],
                        "stats-number": ["56px", {
                            "lineHeight": "1",
                            "fontWeight": "700"
                        }],
                        "headline-md": ["24px", {
                            "lineHeight": "1.4",
                            "fontWeight": "600"
                        }],
                        "headline-xl-mobile": ["32px", {
                            "lineHeight": "1.2",
                            "fontWeight": "700"
                        }]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
            vertical-align: middle
        }

        .bg-hero-pattern {
            background-image: linear-gradient(rgba(0, 40, 142, 0.6), rgba(0, 40, 142, 0.6)), url(https://lh3.googleusercontent.com/aida-public/AB6AXuABiRsmVPX7i1Gjl9oPV06Xb-t-9MJV8bVgQ8QWvKTn4MDoAfPc8QNGx_dRjHK4Q-S0tSEv4g6SxSa6QftW6PTZ3uVZyKNZdiuJLCqy2k36vPAkuk8OWRZiVR7zP4ITwjWtL-Q-DWZcerLssa-yv_WVBtFBHOkOA_0TZeQKPoEpQvXtf6USo_BexaERVIvW6rKRUWeJVCiwq5poyOEd0xKqvOTBhaPHlxwn7TTNTAmVIDAuboC0NpePRazp_0UQGOaCR2t2k_WI-Yc);
            background-size: cover;
            background-position: center
        }

        [x-cloak] {
            display: none;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-background text-on-surface font-body-md overflow-x-hidden">
    <!-- TopNavBar -->
    <header class="bg-surface/90 backdrop-blur-md fixed top-0 w-full z-50 shadow-[0_4px_20px_rgba(30,64,175,0.08)]"
        x-data="{ mobileMenuOpen: false }">
        <div class="max-w-container-max mx-auto px-gutter flex justify-between items-center h-20">
            <div class="font-headline-md text-headline-md font-bold text-primary">{{ $schoolName }}</div>
            <nav class="hidden md:flex gap-8 items-center">
                <a class="text-primary border-b-2 border-primary pb-1 font-bold font-label-md text-label-md"
                    href="#hero">Home</a>
                <a class="text-on-surface-variant font-medium font-label-md text-label-md hover:text-primary transition-colors duration-200"
                    href="#statistik">Info Sekolah</a>
                <a class="text-on-surface-variant font-medium font-label-md text-label-md hover:text-primary transition-colors duration-200"
                    href="#visi-misi">Visi &amp; Misi</a>
                <a class="text-on-surface-variant font-medium font-label-md text-label-md hover:text-primary transition-colors duration-200"
                    href="#berita">Berita</a>
                <a class="text-on-surface-variant font-medium font-label-md text-label-md hover:text-primary transition-colors duration-200"
                    href="#kontak">Kontak</a>
                <a class="bg-secondary-container text-on-secondary-container font-label-md text-label-md px-5 py-2 rounded-full hover:opacity-90 transition-all"
                    href="{{ route('ppdb.informasi') }}">Daftar SPMB</a>
                @auth
                    <a class="bg-primary text-on-primary font-label-md text-label-md px-5 py-2 rounded-full hover:opacity-90 transition-all"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="bg-surface-container-highest text-on-surface font-label-md text-label-md px-5 py-2 rounded-full hover:opacity-90 transition-all"
                            type="submit">Keluar</button>
                    </form>
                @else
                    <a class="bg-primary text-on-primary font-label-md text-label-md px-5 py-2 rounded-full hover:opacity-90 transition-all"
                        href="{{ route('login') }}">Masuk</a>
                @endauth
            </nav>
            <div class="flex items-center gap-4">
                <button class="material-symbols-outlined text-primary p-2">search</button>
                <button class="md:hidden material-symbols-outlined text-primary p-2 cursor-pointer"
                    @click="mobileMenuOpen = !mobileMenuOpen">menu</button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <nav x-cloak x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false"
            class="md:hidden absolute top-full left-0 right-0 bg-surface border-b border-surface-variant shadow-lg z-40">
            <div class="max-w-container-max mx-auto px-gutter py-4 flex flex-col gap-2">
                <a class="text-primary border-b-2 border-primary pb-2 font-bold font-label-md text-label-md"
                    href="#hero" @click="mobileMenuOpen = false">Home</a>
                <a class="text-on-surface-variant font-medium font-label-md text-label-md hover:text-primary transition-colors duration-200 pb-2"
                    href="#statistik" @click="mobileMenuOpen = false">Info Sekolah</a>
                <a class="text-on-surface-variant font-medium font-label-md text-label-md hover:text-primary transition-colors duration-200 pb-2"
                    href="#visi-misi" @click="mobileMenuOpen = false">Visi &amp; Misi</a>
                <a class="text-on-surface-variant font-medium font-label-md text-label-md hover:text-primary transition-colors duration-200 pb-2"
                    href="#berita" @click="mobileMenuOpen = false">Berita</a>
                <a class="text-on-surface-variant font-medium font-label-md text-label-md hover:text-primary transition-colors duration-200 pb-2"
                    href="#kontak" @click="mobileMenuOpen = false">Kontak</a>
                <div class="border-t border-surface-variant pt-4 mt-2 flex flex-col gap-2">
                    <a class="bg-secondary-container text-on-secondary-container font-label-md text-label-md px-5 py-2 rounded-full hover:opacity-90 transition-all text-center"
                        href="{{ route('ppdb.informasi') }}" @click="mobileMenuOpen = false">Daftar SPMB</a>
                    @auth
                        <a class="bg-primary text-on-primary font-label-md text-label-md px-5 py-2 rounded-full hover:opacity-90 transition-all text-center"
                            href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                class="w-full bg-surface-container-highest text-on-surface font-label-md text-label-md px-5 py-2 rounded-full hover:opacity-90 transition-all"
                                type="submit">Keluar</button>
                        </form>
                    @else
                        <a class="bg-primary text-on-primary font-label-md text-label-md px-5 py-2 rounded-full hover:opacity-90 transition-all text-center"
                            href="{{ route('login') }}" @click="mobileMenuOpen = false">Masuk</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>
    <!-- Hero Slider -->
    <section id="hero"
        class="relative h-[600px] md:h-[800px] flex items-center justify-center text-center overflow-hidden pt-20">
        <div class="absolute inset-0 z-0">
            <img class="w-full h-full object-cover"
                data-alt="A wide-angle photo of primary school children engaged in a vibrant outdoor science experiment. They are wearing clean blue uniforms and safety goggles, looking excited and curious under the bright morning sun. The background shows a modern school building with soft blue and yellow architectural accents. The overall atmosphere is joyful, professional, and full of educational wonder."
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUEN1X4a5BEdrCH6oY6N4H8-r6HsMBmwPVrEtkiusjOgLqs5bi2YgcARVe9aoX7PPpn-1C9wM8enzqtSINk-kTRv7FlOekek11Dmr6UpqpXpAthUbgeI9lI63B2AaeVFmzvL3hFLDMhEzds2yYUtN-t5G2VWFjgc0NbClBRXWdaTO2aHoRBnObLreJT3PIiGq2H1Zbivd62t5v-PLQNLJEfJ77tWLa9elAqfzcrKE3FvSyL2-WYadyghsiB_nx_M9GYyEcEyOdhVs" />
            <div class="absolute inset-0 bg-primary/40 mix-blend-multiply"></div>
        </div>
        <div class="relative z-10 max-w-4xl px-gutter">
            <span
                class="inline-block bg-secondary-container text-on-secondary-container font-label-md text-label-md px-6 py-2 rounded-full mb-6">Motto
                Sekolah</span>
            <h1 class="text-white font-headline-xl text-headline-xl md:text-headline-xl mb-8">{{ $schoolMotto }}</h1>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ route('ppdb.informasi') }}"
                    class="inline-block bg-primary text-on-primary font-label-md text-label-md px-10 py-4 rounded-3xl shadow-lg hover:opacity-90 transition-all">Daftar
                    Sekarang</a>
                <a href="#statistik"
                    class="inline-block bg-secondary-container text-on-secondary-container font-label-md text-label-md px-10 py-4 rounded-3xl shadow-lg hover:opacity-90 transition-all">Eksplor
                    Program</a>
            </div>
        </div>
        <!-- Custom Slider Dots -->
        <div class="absolute bottom-12 flex gap-3">
            <div class="w-12 h-3 bg-secondary-container rounded-full transition-all duration-300"></div>
            <div class="w-3 h-3 bg-white/50 rounded-full"></div>
            <div class="w-3 h-3 bg-white/50 rounded-full"></div>
        </div>
    </section>
    <!-- Statistics Section -->
    <section id="statistik" class="relative -mt-20 z-20 max-w-container-max mx-auto px-gutter">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div
                class="bg-surface-container-lowest p-10 rounded-3xl shadow-[0_4px_20px_rgba(30,64,175,0.08)] flex flex-col items-center text-center border-b-4 border-secondary">
                <span class="material-symbols-outlined text-secondary text-5xl mb-4"
                    style="font-variation-settings: 'FILL' 1;">person</span>
                <div class="font-stats-number text-stats-number text-primary mb-2">45</div>
                <div class="font-label-md text-label-md text-on-surface-variant">Jumlah Guru</div>
            </div>
            <div
                class="bg-surface-container-lowest p-10 rounded-3xl shadow-[0_4px_20px_rgba(30,64,175,0.08)] flex flex-col items-center text-center border-b-4 border-primary">
                <span class="material-symbols-outlined text-primary text-5xl mb-4"
                    style="font-variation-settings: 'FILL' 1;">groups</span>
                <div class="font-stats-number text-stats-number text-primary mb-2">850</div>
                <div class="font-label-md text-label-md text-on-surface-variant">Jumlah Siswa</div>
            </div>
            <div
                class="bg-surface-container-lowest p-10 rounded-3xl shadow-[0_4px_20px_rgba(30,64,175,0.08)] flex flex-col items-center text-center border-b-4 border-secondary">
                <span class="material-symbols-outlined text-secondary text-5xl mb-4"
                    style="font-variation-settings: 'FILL' 1;">military_tech</span>
                <div class="font-stats-number text-stats-number text-primary mb-2">120</div>
                <div class="font-label-md text-label-md text-on-surface-variant">Jumlah Prestasi</div>
            </div>
        </div>
    </section>
    <!-- School Info Section (Asymmetric Grid) -->
    <section id="info" class="py-section-padding-desktop max-w-container-max mx-auto px-gutter">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-stretch">
            <div class="lg:col-span-4 bg-primary text-on-primary p-12 rounded-3xl flex flex-col justify-center">
                <h2 class="font-headline-lg text-headline-lg mb-6">Informasi Sekolah</h2>
                <p class="font-body-md text-body-md opacity-90">{{ $schoolDescription }}</p>
            </div>
            <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-gutter">
                @forelse ($infoCards as $item)
                    <div
                        class="bg-surface-container p-8 rounded-3xl flex items-start gap-4 hover:shadow-md transition-shadow">
                        <div class="bg-white p-3 rounded-2xl text-primary material-symbols-outlined">
                            {{ $item['icon'] }}</div>
                        <div>
                            <div class="font-label-md text-label-md text-primary mb-1 uppercase tracking-wider">
                                {{ $item['label'] }}</div>
                            <div class="font-body-md text-body-md">{{ $item['value'] }}</div>
                        </div>
                    </div>
                @empty
                    <div
                        class="md:col-span-2 bg-surface-container p-8 rounded-3xl text-center text-on-surface-variant">
                        Informasi sekolah belum tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Visi & Misi Section (Bento Layout) -->
    <section id="visi-misi" class="bg-surface-container-low py-section-padding-desktop">
        <div class="max-w-container-max mx-auto px-gutter">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1">
                    <div class="mb-12">
                        <h2 class="font-headline-xl text-headline-xl text-primary mb-6">Visi</h2>
                        <div class="p-8 bg-white rounded-3xl border-l-8 border-secondary shadow-sm">
                            <p class="font-headline-md text-headline-md text-on-surface italic">
                                "{{ $vision ?? 'Visi sekolah belum tersedia.' }}"</p>
                        </div>
                    </div>
                    <div>
                        <h2 class="font-headline-xl text-headline-xl text-primary mb-6">Misi</h2>
                        <ul class="space-y-4">
                            @forelse ($missions as $mission)
                                <li class="flex gap-4 items-start">
                                    <span
                                        class="bg-primary text-on-primary w-8 h-8 rounded-full flex items-center justify-center shrink-0 font-bold">{{ $loop->iteration }}</span>
                                    <p class="font-body-lg text-body-lg">{{ $mission }}</p>
                                </li>
                            @empty
                                <li class="font-body-lg text-body-lg">Misi sekolah belum tersedia.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <div class="relative rounded-3xl overflow-hidden aspect-square">
                        <img class="w-full h-full object-cover"
                            data-alt="A clean, minimalist high-key photograph of a diverse group of smiling children looking towards a bright future. They are standing in a modern school hallway with high ceilings and plenty of natural sunlight. The soft blue and warm yellow color palette from the school's branding is reflected in the interior design and student clothing. The lighting is ethereal and optimistic, emphasizing a sense of hope and professional educational guidance."
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDN6SCLcluy4Tuvd3wia0ZGsk_i5JMCDAzUz-vjLTSj7JzR21H0-8TuguLs4aV4Ag7HR9jOjeJ73d7VJjedHYDX9i879C3EwtwtV5PU15D0Vke9FTf_1u3te2VpXbVRiWzb5zEC1E12fLPT6UtnkpVPCTE54LBEGi14QB6osfKzTaLyFyKpTO6qcp1H3YQDVrxLVrY9TO8lnLfQlyFs5tWOSW1IFWFkodeD3Qgy4WqwHkYyEC86yzWeYob4DIO2p7ctytSygyVIRvM" />
                        <div class="absolute inset-0 border-24 border-white/20"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- News & Achievements Section -->
    <section id="berita" class="py-section-padding-desktop max-w-container-max mx-auto px-gutter">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- News Column -->
            <div>
                <div class="flex justify-between items-end mb-10">
                    <h3 class="font-headline-lg text-headline-lg text-primary border-l-4 border-secondary pl-4">Berita
                        &amp; Pengumuman</h3>
                    <a class="text-primary font-label-md text-label-md hover:underline" href="#berita">Lihat Semua</a>
                </div>
                <div class="space-y-6">
                    @forelse ($newsArticles->take(2) as $article)
                        <a href="{{ route('berita.show', $article['slug']) }}"
                            class="group flex gap-4 p-4 rounded-2xl hover:bg-white hover:shadow-md transition-all cursor-pointer">
                            <div
                                class="w-24 h-24 rounded-xl overflow-hidden shrink-0 bg-surface-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-4xl">article</span>
                            </div>
                            <div>
                                <span class="text-secondary font-label-md text-label-md">{{ $article['date'] }}</span>
                                <h4
                                    class="font-headline-md text-headline-md text-on-surface line-clamp-2 group-hover:text-primary transition-colors">
                                    {{ $article['title'] }}</h4>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-2xl bg-white p-6 text-on-surface-variant shadow-sm">Belum ada berita yang
                            dipublikasikan.</div>
                    @endforelse
                    @if ($newsArticles->count() > 2)
                        <div class="pl-4 space-y-4 pt-4 border-t border-outline-variant">
                            @foreach ($newsArticles->slice(2) as $article)
                                <a href="{{ route('berita.show', $article['slug']) }}"
                                    class="flex justify-between items-center group cursor-pointer">
                                    <span
                                        class="font-body-md text-body-md group-hover:text-primary">{{ $article['title'] }}</span>
                                    <span class="text-on-surface-variant text-sm">{{ $article['date'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <!-- Achievements Column -->
            <div>
                <div class="flex justify-between items-end mb-10">
                    <h3 class="font-headline-lg text-headline-lg text-primary border-l-4 border-secondary pl-4">
                        Prestasi Siswa</h3>
                    <a class="text-primary font-label-md text-label-md hover:underline" href="#">Lihat Semua</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-secondary-fixed/30 p-6 rounded-3xl flex flex-col items-center text-center">
                        <div class="bg-white p-4 rounded-full shadow-sm mb-4">
                            <span class="material-symbols-outlined text-secondary text-3xl"
                                style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
                        </div>
                        <h4 class="font-label-md text-label-md text-primary mb-2 uppercase">Juara 1</h4>
                        <p class="font-body-md text-body-md font-bold">Olimpiade Sains Nasional Tk. Provinsi</p>
                    </div>
                    <div class="bg-primary-fixed/30 p-6 rounded-3xl flex flex-col items-center text-center">
                        <div class="bg-white p-4 rounded-full shadow-sm mb-4">
                            <span class="material-symbols-outlined text-primary text-3xl"
                                style="font-variation-settings: 'FILL' 1;">emoji_events</span>
                        </div>
                        <h4 class="font-label-md text-label-md text-primary mb-2 uppercase">Medali Emas</h4>
                        <p class="font-body-md text-body-md font-bold">Kejuaraan Karate Junior Se-Jabodetabek</p>
                    </div>
                    <div class="bg-primary-fixed/30 p-6 rounded-3xl flex flex-col items-center text-center">
                        <div class="bg-white p-4 rounded-full shadow-sm mb-4">
                            <span class="material-symbols-outlined text-primary text-3xl"
                                style="font-variation-settings: 'FILL' 1;">military_tech</span>
                        </div>
                        <h4 class="font-label-md text-label-md text-primary mb-2 uppercase">Juara Umum</h4>
                        <p class="font-body-md text-body-md font-bold">Lomba Cerdas Cermat Tk. Kota</p>
                    </div>
                    <div class="bg-secondary-fixed/30 p-6 rounded-3xl flex flex-col items-center text-center">
                        <div class="bg-white p-4 rounded-full shadow-sm mb-4">
                            <span class="material-symbols-outlined text-secondary text-3xl"
                                style="font-variation-settings: 'FILL' 1;">rewarded_ads</span>
                        </div>
                        <h4 class="font-label-md text-label-md text-primary mb-2 uppercase">Favorit</h4>
                        <p class="font-body-md text-body-md font-bold">Festival Seni &amp; Tari Tradisional Nasional
                        </p>
                    </div>
                    <div
                        class="sm:col-span-2 bg-surface-container-highest p-6 rounded-3xl flex items-center justify-center gap-4">
                        <span class="material-symbols-outlined text-on-surface-variant">stars</span>
                        <p class="font-body-md text-body-md">Dan +50 Prestasi Lainnya di Tahun 2024</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Form Section -->
    <section id="kontak" class="bg-primary py-section-padding-desktop">
        <div class="max-w-container-max mx-auto px-gutter">
            <div class="bg-white rounded-[40px] p-10 md:p-20 shadow-2xl overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-secondary-container/20 rounded-full -mr-32 -mt-32">
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 relative z-10">
                    <div>
                        <h2 class="font-headline-xl text-headline-xl text-primary mb-6">Hubungi Kami</h2>
                        <p class="font-body-lg text-body-lg text-on-surface-variant mb-8">Punya pertanyaan seputar
                            pendaftaran atau program sekolah? Kirimkan pesan Anda melalui formulir di bawah ini.</p>
                        <div class="space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="bg-primary-container text-white p-3 rounded-2xl material-symbols-outlined">
                                    schedule</div>
                                <div>
                                    <div class="font-label-md text-label-md text-primary">Jam Kerja</div>
                                    <div class="font-body-md text-body-md">Senin - Jumat, 07:00 - 15:00</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="bg-primary-container text-white p-3 rounded-2xl material-symbols-outlined">
                                    support_agent</div>
                                <div>
                                    <div class="font-label-md text-label-md text-primary">Customer Service</div>
                                    <div class="font-body-md text-body-md">+62 812 3456 7890</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Nama
                                    Lengkap</label>
                                <input
                                    class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-2xl p-4 transition-all"
                                    placeholder="Masukkan nama Anda" type="text" />
                            </div>
                            <div>
                                <label
                                    class="block font-label-md text-label-md text-on-surface-variant mb-2">Email</label>
                                <input
                                    class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-2xl p-4 transition-all"
                                    placeholder="nama@email.com" type="email" />
                            </div>
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-2">No HP /
                                WhatsApp</label>
                            <input
                                class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-2xl p-4 transition-all"
                                placeholder="08xxxxxxxxx" type="text" />
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Pesan</label>
                            <textarea
                                class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-2xl p-4 transition-all"
                                placeholder="Tuliskan pesan Anda di sini..." rows="4"></textarea>
                        </div>
                        <button
                            class="w-full bg-secondary-container text-on-secondary-container font-label-md text-label-md py-4 rounded-2xl shadow-lg hover:scale-[1.02] active:scale-95 transition-all"
                            type="button">Kirim Pesan Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="bg-surface-container-highest w-full rounded-t-xl mt-12">
        <div
            class="max-w-container-max mx-auto px-gutter py-section-padding-desktop grid grid-cols-1 md:grid-cols-4 gap-gutter">
            <div class="space-y-6">
                <div class="font-headline-md text-headline-md font-bold text-on-surface">{{ $schoolName }}</div>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ $schoolDescription }}</p>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-primary mb-6 uppercase">Kontak Kami</h4>
                <ul class="space-y-4 text-on-surface-variant font-body-md text-body-md">
                    <li>Alamat: {{ $contactAddress }}</li>
                    <li>Email: {{ $contactEmail }}</li>
                    <li>Telp: {{ $contactPhone }}</li>
                    <li>NPSN: {{ $schoolNpsn }}</li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-primary mb-6 uppercase">Tautan Cepat</h4>
                <ul class="space-y-4 text-on-surface-variant font-body-md text-body-md">
                    <li><a class="hover:text-secondary transition-all" href="#">Kurikulum</a></li>
                    <li><a class="hover:text-secondary transition-all" href="#">Fasilitas</a></li>
                    <li><a class="hover:text-secondary transition-all"
                            href="{{ route('ppdb.informasi') }}">Pendaftaran SPMB</a></li>
                    <li><a class="hover:text-secondary transition-all" href="#">Ekstrakurikuler</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-primary mb-6 uppercase">Ikuti Kami</h4>
                <div class="flex gap-4">
                    <a class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center hover:bg-secondary transition-all"
                        href="#">
                        <span class="material-symbols-outlined text-sm">face_nod</span>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center hover:bg-secondary transition-all"
                        href="#">
                        <span class="material-symbols-outlined text-sm">photo_camera</span>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center hover:bg-secondary transition-all"
                        href="#">
                        <span class="material-symbols-outlined text-sm">alternate_email</span>
                    </a>
                </div>
                <div class="mt-8 text-on-surface-variant text-sm">
                    © 2024 {{ $schoolName }}. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
    <!-- SideNavBar (Floating Quick Contact) -->
    <div class="fixed bottom-8 right-8 z-100">
        <div class="group relative">
            <!-- Tooltip -->
            <div
                class="absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-secondary text-on-secondary px-4 py-2 rounded-xl text-label-md font-label-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                Chat with us
            </div>
            <!-- FAB -->
            <button
                class="bg-secondary dark:bg-secondary-fixed text-on-secondary dark:text-on-secondary-fixed rounded-full p-4 w-16 h-16 flex items-center justify-center shadow-[0_24px_48px_rgba(30,64,175,0.15)] hover:scale-110 hover:shadow-xl transition-all duration-300 active:scale-90">
                <span class="material-symbols-outlined text-3xl"
                    style="font-variation-settings: 'FILL' 1;">chat</span>
            </button>
        </div>
    </div>
</body>

</html>
