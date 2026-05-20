<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Semua Berita - {{ $schoolName ?? config('app.name', 'Elementary School') }}</title>
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
                    <p class="text-xs text-on-surface-variant">Pusat Berita Sekolah</p>
                </div>
            </a>
            <div class="flex items-center gap-3 text-sm font-semibold">
                <a href="{{ route('depan.beranda') }}" class="text-on-surface-variant hover:text-primary">Beranda</a>
                <a href="{{ route('prestasi.index') }}" class="text-on-surface-variant hover:text-primary">Prestasi</a>
                <a href="{{ route('ppdb.informasi') }}" class="text-on-surface-variant hover:text-primary">SPMB</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <section class="rounded-[2rem] bg-primary px-6 py-10 text-white shadow-sm sm:px-10">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-white/75">Informasi Publik</p>
            <h1 class="mt-4 font-headline text-4xl font-bold leading-tight sm:text-5xl">Semua Berita</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-white/85">Kumpulan berita, pengumuman, dan pembaruan
                terbaru dari sekolah.</p>
        </section>

        <section class="mt-8">
            @if ($articles->isEmpty())
                <div
                    class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-on-surface-variant shadow-sm">
                    Belum ada berita yang dipublikasikan.
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($articles as $article)
                        <article
                            class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="flex items-start justify-between gap-4">
                                <span
                                    class="rounded-full bg-secondary-container px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-on-secondary-container">
                                    {{ $article->category }}
                                </span>
                                <span
                                    class="text-xs text-on-surface-variant">{{ $article->published_at?->translatedFormat('d M Y') ?? '-' }}</span>
                            </div>
                            <h2 class="mt-5 font-headline text-2xl font-bold text-primary">{{ $article->title }}</h2>
                            <p class="mt-3 text-sm leading-7 text-on-surface-variant">{{ $article->excerpt }}</p>
                            <div class="mt-6">
                                <a href="{{ route('berita.show', ['slug' => $article->slug]) }}"
                                    class="inline-flex items-center gap-2 rounded-full bg-primary px-4 py-2 text-sm font-bold text-white transition hover:bg-primary-container">
                                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    Baca Detail
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @endif
        </section>
    </main>
</body>

</html>
