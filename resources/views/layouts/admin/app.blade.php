<!DOCTYPE html>

<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Dashboard Administrasi SD Cerdas</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="stylesheet" href="//unpkg.com/jodit@4.1.16/es2021/jodit.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&amp;family=Nunito+Sans:wght@300;400;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
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
                        "secondary": "#795900",
                        "surface-container-highest": "#e0e3e5",
                        "surface-bright": "#f7f9fb",
                        "secondary-fixed": "#ffdf9f",
                        "on-primary-container": "#a8b8ff",
                        "surface-container": "#eceef0",
                        "on-surface": "#191c1e",
                        "on-tertiary-container": "#ffa583",
                        "primary-container": "#1e40af",
                        "background": "#f7f9fb",
                        "on-tertiary-fixed-variant": "#802a00",
                        "on-background": "#191c1e",
                        "outline": "#757684",
                        "surface-variant": "#e0e3e5",
                        "primary-fixed": "#dde1ff",
                        "on-secondary-fixed": "#261a00",
                        "surface-container-high": "#e6e8ea",
                        "on-primary-fixed": "#001453",
                        "secondary-container": "#ffc329",
                        "tertiary-fixed-dim": "#ffb59a",
                        "on-tertiary": "#ffffff",
                        "inverse-on-surface": "#eff1f3",
                        "on-surface-variant": "#444653",
                        "on-error-container": "#93000a",
                        "on-secondary-container": "#6f5100",
                        "on-secondary-fixed-variant": "#5c4300",
                        "primary": "#00288e",
                        "outline-variant": "#c4c5d5",
                        "surface-container-low": "#f2f4f6",
                        "surface-dim": "#d8dadc",
                        "surface-tint": "#3755c3",
                        "on-primary-fixed-variant": "#173bab",
                        "error": "#ba1a1a",
                        "on-secondary": "#ffffff",
                        "inverse-primary": "#b8c4ff",
                        "tertiary-fixed": "#ffdbce",
                        "tertiary": "#611e00",
                        "secondary-fixed-dim": "#f9bd22",
                        "primary-fixed-dim": "#b8c4ff",
                        "on-primary": "#ffffff",
                        "inverse-surface": "#2d3133",
                        "tertiary-container": "#872d00",
                        "surface-container-lowest": "#ffffff",
                        "on-error": "#ffffff",
                        "error-container": "#ffdad6",
                        "surface": "#f7f9fb",
                        "on-tertiary-fixed": "#380d00"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "section-padding-mobile": "40px",
                        "container-max": "1280px",
                        "gutter": "24px",
                        "section-padding-desktop": "80px",
                        "base": "8px"
                    },
                    "fontFamily": {
                        "stats-number": ["Quicksand"],
                        "body-lg": ["Nunito Sans"],
                        "headline-lg": ["Quicksand"],
                        "label-md": ["Nunito Sans"],
                        "headline-md": ["Quicksand"],
                        "headline-xl": ["Quicksand"],
                        "body-md": ["Nunito Sans"],
                        "headline-xl-mobile": ["Quicksand"]
                    },
                    "fontSize": {
                        "stats-number": ["56px", {
                            "lineHeight": "1",
                            "fontWeight": "700"
                        }],
                        "body-lg": ["18px", {
                            "lineHeight": "1.6",
                            "fontWeight": "400"
                        }],
                        "headline-lg": ["32px", {
                            "lineHeight": "1.3",
                            "fontWeight": "600"
                        }],
                        "label-md": ["14px", {
                            "lineHeight": "1.2",
                            "letterSpacing": "0.05em",
                            "fontWeight": "700"
                        }],
                        "headline-md": ["24px", {
                            "lineHeight": "1.4",
                            "fontWeight": "600"
                        }],
                        "headline-xl": ["48px", {
                            "lineHeight": "1.2",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "body-md": ["16px", {
                            "lineHeight": "1.6",
                            "fontWeight": "400"
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
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            background-color: #f7f9fb;
        }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: auto auto;
            gap: 24px;
        }

        [x-cloak] {
            display: none;
        }
    </style>
    @livewireStyles()
</head>

<body class="h-screen overflow-hidden font-body-md text-on-background antialiased" x-data="{ sidebarOpen: false }"
    @close-sidebar.window="sidebarOpen = false">
    <!-- SideNavBar (Authority: JSON) -->
    <!-- Mobile Sidebar Backdrop -->
    <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden">
    </div>

    <aside data-admin-sidebar
        class="fixed left-0 top-0 z-1 flex h-full w-72 flex-col overflow-y-auto border-r border-outline-variant/20 bg-surface-container-lowest shadow-md dark:bg-inverse-surface -translate-x-full lg:translate-x-0 transition-transform duration-300"
        :class="{ 'translate-x-0': sidebarOpen }">
        <div class="p-8 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white" data-icon="school">school</span>
            </div>
            <div>
                <h1
                    class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed leading-tight">
                    Admin</h1>
                <p class="font-label-md text-label-md text-on-surface-variant opacity-70">Admin Portal</p>
            </div>
        </div>
        <div class="px-6 mb-6">

        </div>
        @php
            $menuBase = 'flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-200';
            $menuActive = 'bg-surface-container text-primary font-bold border-r-4 border-primary';
            $menuInactive = 'text-on-surface-variant hover:text-primary hover:bg-surface-container';

            $submenuBase = 'ml-11 block rounded-lg px-3 py-2 text-sm transition-colors duration-200';
            $submenuActive = 'bg-primary/10 text-primary font-semibold';
            $submenuInactive = 'text-on-surface-variant hover:text-primary hover:bg-surface-container';

            $to = function (string $routeName): string {
                return \Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : url()->current();
            };

            $isDashboard = request()->routeIs('admin.dashboard');
            $isAkademik = request()->routeIs('admin.akademik.*');
            $isPublik = request()->routeIs('admin.publik.*');
            $isPpdb = request()->routeIs('admin.ppdb.*');
            $isUserManagement = request()->routeIs('admin.users.*');
        @endphp

        <nav class="flex-1 space-y-2 px-4">
            <a wire:navigate href="{{ route('admin.dashboard') }}"
                class="{{ $menuBase }} {{ $isDashboard ? $menuActive : $menuInactive }}">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span class="font-label-md text-label-md">Dashboard</span>
            </a>

            <div class="space-y-1">
                <a wire:navigate href="{{ $to('admin.publik.index') }}"
                    class="{{ $menuBase }} {{ $isPublik ? $menuActive : $menuInactive }}">
                    <span class="material-symbols-outlined" data-icon="home">home</span>
                    <span class="font-label-md text-label-md">Publik</span>
                </a>
                <div class="space-y-1">
                    <a wire:navigate href="{{ $to('admin.publik.index') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.publik.index') ? $submenuActive : $submenuInactive }}">Ringkasan
                        Publik</a>
                    <a wire:navigate href="{{ $to('admin.publik.info-sekolah') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.publik.info-sekolah') ? $submenuActive : $submenuInactive }}">Info
                        Sekolah</a>
                    <a wire:navigate href="{{ $to('admin.publik.visi-misi') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.publik.visi-misi') ? $submenuActive : $submenuInactive }}">Visi
                        Misi</a>
                    <a wire:navigate href="{{ $to('admin.publik.berita') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.publik.berita') ? $submenuActive : $submenuInactive }}">Berita</a>
                    <a wire:navigate href="{{ $to('admin.publik.prestasi') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.publik.prestasi') ? $submenuActive : $submenuInactive }}">Prestasi</a>
                </div>
            </div>

            <div class="space-y-1">
                <a wire:navigate href="{{ $to('admin.akademik.index') }}"
                    class="{{ $menuBase }} {{ $isAkademik ? $menuActive : $menuInactive }}">
                    <span class="material-symbols-outlined" data-icon="school">school</span>
                    <span class="font-label-md text-label-md">Akademik</span>
                </a>
                <div class="space-y-1">
                    <a wire:navigate href="{{ $to('admin.akademik.index') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.akademik.index') ? $submenuActive : $submenuInactive }}">Ringkasan
                        Akademik</a>
                    <a wire:navigate href="{{ $to('admin.akademik.siswa') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.akademik.siswa') ? $submenuActive : $submenuInactive }}">Siswa</a>
                    <a wire:navigate href="{{ $to('admin.akademik.guru') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.akademik.guru') ? $submenuActive : $submenuInactive }}">Guru</a>
                    <a wire:navigate href="{{ $to('admin.akademik.kelas') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.akademik.kelas') ? $submenuActive : $submenuInactive }}">Kelas</a>
                </div>
            </div>



            <div class="space-y-1">
                <a wire:navigate href="{{ $to('admin.ppdb.index') }}"
                    class="{{ $menuBase }} {{ $isPpdb ? $menuActive : $menuInactive }}">
                    <span class="material-symbols-outlined" data-icon="person_add">person_add</span>
                    <span class="font-label-md text-label-md">SPMB</span>
                </a>
                <div class="space-y-1">
                    <a wire:navigate href="{{ $to('admin.ppdb.index') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.ppdb.index') ? $submenuActive : $submenuInactive }}">Ringkasan
                        SPMB</a>
                    <a wire:navigate href="{{ route('admin.ppdb.pendaftar') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.ppdb.pendaftar') ? $submenuActive : $submenuInactive }}">Semua
                        Peserta</a>
                    <a wire:navigate href="{{ $to('admin.ppdb.belum-validasi') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.ppdb.belum-validasi') ? $submenuActive : $submenuInactive }}">Belum
                        Validasi</a>
                    <a wire:navigate href="{{ $to('admin.ppdb.lulus') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.ppdb.lulus') ? $submenuActive : $submenuInactive }}">Peserta
                        Lulus</a>
                    <a wire:navigate href="{{ $to('admin.ppdb.cadangan') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.ppdb.cadangan') ? $submenuActive : $submenuInactive }}">Peserta
                        Cadangan</a>
                    <a wire:navigate href="{{ $to('admin.ppdb.ditolak') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.ppdb.ditolak') ? $submenuActive : $submenuInactive }}">Peserta
                        Ditolak</a>
                </div>
            </div>

            <div class="space-y-1">
                <a wire:navigate href="{{ $to('admin.users.index') }}"
                    class="{{ $menuBase }} {{ $isUserManagement ? $menuActive : $menuInactive }}">
                    <span class="material-symbols-outlined" data-icon="manage_accounts">manage_accounts</span>
                    <span class="font-label-md text-label-md">Kelola User</span>
                </a>
                <div class="space-y-1">
                    <a wire:navigate href="{{ $to('admin.users.index') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.users.index') ? $submenuActive : $submenuInactive }}">Data
                        User</a>
                    <a wire:navigate href="{{ $to('admin.users.roles') }}"
                        class="{{ $submenuBase }} {{ request()->routeIs('admin.users.roles') ? $submenuActive : $submenuInactive }}">Role</a>
                </div>
            </div>

            <div class="pt-8 mt-8 border-t border-outline-variant/20">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-error transition-colors duration-200 hover:bg-error-container/10">
                        <span class="material-symbols-outlined" data-icon="logout">logout</span>
                        <span class="font-label-md text-label-md">Keluar</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>
    <!-- TopAppBar (Authority: JSON) -->
    <header
        class="flex justify-between items-center h-16 px-4 lg:px-8 w-full lg:ml-72 lg:w-[calc(100%-18rem)] fixed top-0 right-0 z-1 bg-surface-container-lowest/80 backdrop-blur-md dark:bg-surface-dim/80 shadow-sm">
        <div class="flex items-center gap-3">
            <button
                class="lg:hidden text-on-surface-variant hover:text-primary transition-colors p-1 rounded-lg hover:bg-surface-container"
                @click="sidebarOpen = !sidebarOpen">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <h2 class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed">Dashboard
                Administrasi</h2>
        </div>
        <div class="flex items-center gap-6">
            <div class="relative focus-within:ring-2 focus-within:ring-primary rounded-full">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant"
                    data-icon="search">search</span>
                <input
                    class="bg-surface-container border-none rounded-full py-2 pl-10 pr-4 w-64 text-sm focus:outline-none transition-all"
                    placeholder="Cari data..." type="text" />
            </div>
            <div class="flex items-center gap-4">
                <button class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                </button>
                <button class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined" data-icon="help_outline">help_outline</span>
                </button>
                <div class="h-8 w-px bg-outline-variant/50"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="font-label-md text-[12px] font-bold text-on-surface leading-none">Admin Utama</p>
                        <p class="font-label-md text-[10px] text-on-surface-variant">Super Admin</p>
                    </div>
                    <img alt="Administrator Avatar"
                        class="w-10 h-10 rounded-full border-2 border-primary-fixed object-cover"
                        data-alt="A professional portrait of a male school administrator in a modern office environment. He is smiling warmly, wearing a neat navy blue suit. The background is softly blurred showing a contemporary school corridor with high-key natural lighting and a clean, institutional light-mode aesthetic."
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzKGa7emGVq5ysWi2c5RvdTqyO5zcwXVG0qoZq6JYQdQt9pKRdXXkBUPb1O1RGRwQcJtRvzplHNz3cfhbDnFgdlhMTTdBOT70WF53WzC_JNpZGpQJjhLhXX_tXpFI14KdF1J25DZHnTD-V2VM7B8VptP__fmKj2o2WjEQVnNNXyulhu5W8MfjfeSvFpamA9gYpfxMlgRnbDGhx-T0XNx-FEMXe1B1WDwrhR__BSpaxkBbKHZ2OKd3uwubD4Vl6R2ZlzKcYKTWr2us" />
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content Canvas -->
    <main class="fixed bottom-0 left-0 lg:left-72 right-0 top-16 overflow-y-auto p-8">
        {{ $slot }}

    </main>
    <script>
        (() => {
            const sidebarSelector = '[data-admin-sidebar]';
            const storageKey = 'admin-sidebar-scroll-top';

            const saveSidebarScroll = () => {
                const sidebar = document.querySelector(sidebarSelector);

                if (sidebar) {
                    sessionStorage.setItem(storageKey, String(sidebar.scrollTop));
                }
            };

            const restoreSidebarScroll = () => {
                const sidebar = document.querySelector(sidebarSelector);
                const savedPosition = sessionStorage.getItem(storageKey);

                if (sidebar && savedPosition !== null) {
                    sidebar.scrollTop = Number(savedPosition);
                }
            };

            restoreSidebarScroll();
            document.addEventListener('livewire:navigate', saveSidebarScroll);
            document.addEventListener('livewire:navigated', restoreSidebarScroll);
            document.addEventListener('livewire:navigate', () => {
                window.dispatchEvent(new CustomEvent('close-sidebar'));
            });
        })();
    </script>
    <!-- FAB Suppression: On Dashboard, FAB can be active -->
    {{-- <button
        class="fixed bottom-8 right-8 z-60 flex h-16 w-16 items-center justify-center rounded-full bg-secondary text-on-secondary shadow-2xl transition-all hover:scale-110 active:scale-95">
        <span class="material-symbols-outlined text-3xl" data-icon="chat">chat</span>
    </button> --}}
    <div x-data="{ show: false, type: 'info', message: '', timeout: null }"
        x-on:toast.window="
                show = true;
                type = $event.detail.type ?? 'info';
                message = $event.detail.message ?? 'Proses selesai.';
                clearTimeout(timeout);
                timeout = setTimeout(() => show = false, 2600);
            "
        x-show="show" x-transition.opacity.duration.200ms x-cloak class="fixed inset-x-0 bottom-4 z-50 px-4">
        <div class="mx-auto w-full max-w-sm rounded-xl px-4 py-3 text-sm font-medium text-white shadow-lg"
            :class="{
                'bg-emerald-600': type === 'success',
                'bg-rose-600': type === 'error',
                'bg-amber-500': type === 'warning',
                'bg-sky-600': type === 'info'
            }"
            x-text="message"></div>
    </div>
    @livewireScripts()
    <script src="//unpkg.com/jodit@4.1.16/es2021/jodit.min.js"></script>
</body>

</html>
