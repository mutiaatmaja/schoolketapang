<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $article['title'] }} - {{ config('app.name', 'Elementary School') }}</title>
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
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle
        }
    </style>
</head>

<body class="bg-background font-body text-on-surface antialiased">
    <header class="sticky top-0 z-30 border-b border-white/70 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('depan.beranda') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-white">
                    <span class="material-symbols-outlined text-[20px]">school</span>
                </span>
                <div>
                    <p class="font-headline text-lg font-bold text-primary">Elementary School</p>
                    <p class="text-xs text-on-surface-variant">Berita Sekolah</p>
                </div>
            </a>
            <div class="flex items-center gap-3 text-sm font-semibold">
                <a href="{{ route('depan.beranda') }}" class="text-on-surface-variant hover:text-primary">Beranda</a>
                <a href="{{ route('ppdb.informasi') }}" class="text-on-surface-variant hover:text-primary">SPMB</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <section class="overflow-hidden rounded-3xl bg-primary text-white shadow-sm">
            <div class="grid gap-0 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="p-6 sm:p-8 lg:p-10">
                    <span
                        class="inline-flex rounded-full bg-white/15 px-4 py-2 text-xs font-bold uppercase tracking-[0.24em] text-white/90">
                        {{ $article['category'] }}
                    </span>
                    <h1 class="mt-5 font-headline text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl">
                        {{ $article['title'] }}
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-white/85 sm:text-lg">
                        {{ $article['excerpt'] }}
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-4 text-sm text-white/80">
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2">
                            <span class="material-symbols-outlined text-[18px]">schedule</span>
                            <span>{{ $article['date'] }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2">
                            <span class="material-symbols-outlined text-[18px]">article</span>
                            <span>Detail berita</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 p-6 sm:p-8 lg:p-10">
                    <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-white/70">Ringkasan</p>
                        <ul class="mt-4 space-y-3 text-sm leading-6 text-white/90">
                            @foreach ($article['content'] as $paragraph)
                                <li class="flex gap-3">
                                    <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-secondary-container"></span>
                                    <span>{{ $paragraph }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-8 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-5 text-base leading-8 text-slate-700">
                    @foreach ($article['content'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>

                <div class="mt-8 rounded-2xl bg-slate-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-secondary">Tautan cepat</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a href="{{ route('ppdb.informasi') }}"
                            class="inline-flex items-center gap-2 rounded-full bg-secondary-container px-4 py-2 text-sm font-bold text-on-secondary-container transition hover:opacity-90">
                            <span class="material-symbols-outlined text-[18px]">assignment</span>
                            SPMB
                        </a>
                        <a href="{{ route('depan.beranda') }}"
                            class="inline-flex items-center gap-2 rounded-full bg-primary px-4 py-2 text-sm font-bold text-white transition hover:bg-primary-container">
                            <span class="material-symbols-outlined text-[18px]">home</span>
                            Beranda
                        </a>
                    </div>
                </div>
            </article>

            <aside class="space-y-5">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-secondary">Info artikel</p>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-slate-500">Kategori</dt>
                            <dd class="font-semibold text-slate-800">{{ $article['category'] }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-slate-500">Tanggal</dt>
                            <dd class="font-semibold text-slate-800">{{ $article['date'] }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-slate-500">Slug</dt>
                            <dd class="font-semibold text-slate-800">{{ $slug }}</dd>
                        </div>
                    </dl>
                </div>

                @if (!empty($relatedArticles))
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-secondary">Berita lain</p>
                        <div class="mt-4 space-y-4">
                            @foreach ($relatedArticles as $relatedArticle)
                                <a href="{{ route('berita.show', $relatedArticle['slug']) }}"
                                    class="block rounded-2xl border border-slate-200 p-4 transition hover:border-primary hover:bg-slate-50">
                                    <p class="text-sm font-semibold text-slate-800">{{ $relatedArticle['title'] }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $relatedArticle['date'] }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </section>
    </main>
</body>

</html>
