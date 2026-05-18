<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPMB Online - {{ config('app.name', 'Sekolah SD') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <div
        class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.16),_transparent_42%),linear-gradient(180deg,_#eff6ff_0%,_#f8fafc_24%,_#f8fafc_100%)]">
        <header class="sticky top-0 z-30 border-b border-sky-100 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-md items-center justify-between px-4 py-3 sm:max-w-2xl sm:px-6">
                <a href="{{ route('ppdb.informasi') }}" wire:navigate class="text-sm font-semibold text-sky-700">SPMB
                    Online</a>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <a href="{{ route('depan.beranda') }}"
                        class="rounded-full bg-slate-100 px-3 py-1.5 font-semibold text-slate-600">Beranda</a>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-md px-4 py-5 sm:max-w-2xl sm:px-6 sm:py-8">
            {{ $slot }}
        </main>
    </div>

    <div x-data="{ show: false, type: 'info', message: '', timeout: null }"
        x-on:toast.window="
            show = true;
            type = $event.detail.type ?? 'info';
            message = $event.detail.message ?? 'Proses selesai.';
            clearTimeout(timeout);
            timeout = setTimeout(() => show = false, 2800);
        "
        x-show="show" x-transition.opacity.duration.200ms x-cloak class="fixed inset-x-0 bottom-4 z-50 px-4">
        <div class="mx-auto w-full max-w-sm rounded-2xl px-4 py-3 text-sm font-medium text-white shadow-xl"
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
