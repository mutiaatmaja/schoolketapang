<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sekolah SD') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="{{ route('depan.beranda') }}" wire:navigate class="text-sm font-bold text-sky-700">SD Modern</a>
            <nav class="flex items-center gap-3 text-xs font-medium text-slate-600">
                <a href="{{ route('depan.beranda') }}" wire:navigate class="hover:text-slate-900">Depan</a>
                <a href="{{ route('ppdb.informasi') }}" wire:navigate class="hover:text-slate-900">SPMB</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-slate-900">Admin</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-slate-900">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-slate-900">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl">
        {{ $slot }}
    </main>

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
</body>

</html>
