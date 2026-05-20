<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Semua Prestasi - {{ $schoolName ?? config('app.name', 'Elementary School') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600;700&family=Nunito+Sans:wght@400;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#00288e',
                        'primary-container': '#1e40af',
                        secondary: '#795900',
                        'secondary-container': '#ffc329',
                        background: '#f7f9fb',
                        surface: '#ffffff',
                        'surface-container': '#eceef0',
                        'surface-container-highest': '#e0e3e5',
                        'on-surface': '#191c1e',
                        'on-surface-variant': '#444653',
                        'on-primary': '#ffffff',
                        'on-secondary-container': '#6f5100',
                    },
                    fontFamily: {
                        headline: ['Quicksand'],
                        body: ['Nunito Sans'],
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-background font-body text-on-surface antialiased">
    <header class="sticky top-0 z-30 border-b border-white/70 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('depan.beranda') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-white">
                    <span class="material-symbols-outlined text-[20px]">school</span>
                </span>
                <div>
                    <p class="font-headline text-lg font-bold text-primary">{{ $schoolName ?? 'Nama Sekolah' }}</p>
                    <p class="text-xs text-on-surface-variant">Galeri Prestasi Sekolah</p>
                </div>
            </a>
            <div class="flex items-center gap-3 text-sm font-semibold">
                <a href="{{ route('depan.beranda') }}" class="text-on-surface-variant hover:text-primary">Beranda</a>
                <a href="{{ route('berita.index') }}" class="text-on-surface-variant hover:text-primary">Berita</a>
                <a href="{{ route('ppdb.informasi') }}" class="text-on-surface-variant hover:text-primary">SPMB</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <section
            class="rounded-[2rem] bg-gradient-to-br from-primary to-primary-container px-6 py-10 text-white shadow-sm sm:px-10">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-white/75">Informasi Publik</p>
                    <h1 class="mt-4 font-headline text-4xl font-bold leading-tight sm:text-5xl">Semua Prestasi</h1>
                    <p class="mt-4 max-w-3xl text-base leading-7 text-white/85">Daftar prestasi siswa yang telah diraih
                        sekolah pada berbagai tingkat kompetisi.</p>
                </div>
                <div class="rounded-3xl bg-white/10 px-6 py-4 text-center backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/70">Total Prestasi</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($achievementCount, 0, ',', '.') }}</p>
                </div>
            </div>
        </section>

        <section class="mt-8">
            @if ($achievements->isEmpty())
                <div
                    class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-on-surface-variant shadow-sm">
                    Belum ada data prestasi yang ditampilkan.
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($achievements as $achievement)
                        <article
                            class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="flex items-start justify-between gap-3">
                                <span
                                    class="rounded-full bg-secondary-container px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-on-secondary-container">
                                    {{ $achievement->title }}
                                </span>
                                <span class="text-sm font-semibold text-primary">{{ $achievement->year }}</span>
                            </div>
                            <p class="mt-5 font-headline text-2xl font-bold text-primary">
                                {{ $achievement->description }}</p>
                            <div
                                class="mt-6 inline-flex items-center gap-2 rounded-full bg-surface-container px-4 py-2 text-sm font-semibold text-on-surface-variant">
                                <span
                                    class="material-symbols-outlined text-[18px] text-secondary">workspace_premium</span>
                                {{ $achievement->level }}
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $achievements->links() }}
                </div>
            @endif
        </section>
    </main>
</body>

</html>
