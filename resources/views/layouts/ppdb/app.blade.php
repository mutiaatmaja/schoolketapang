<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPMB {{ $schoolInfo['Nama Sekolah'] ?? 'SD Ketapang' }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>

<body
    class="min-h-screen bg-[radial-gradient(circle_at_top,#f6c453_0%,#f5efe2_28%,#eef4f1_60%,#eef4f1_100%)] text-slate-900">
    <div class="mx-auto flex min-h-screen w-full max-w-md flex-col px-4 pb-8 pt-4 sm:max-w-lg sm:px-5">
        <header
            class="mb-5 flex items-center justify-between rounded-3xl bg-white/80 px-4 py-3 shadow-sm ring-1 ring-white/60 backdrop-blur">
            <a href="{{ route('ppdb.informasi') }}" wire:navigate class="text-sm font-semibold text-[#1d4f45]">SPMB
                {{ $schoolInfo['Nama Sekolah'] ?? 'SD Ketapang' }}</a>
            <div class="flex items-center gap-3 text-xs font-medium text-slate-500">
                <a href="{{ route('ppdb.informasi') }}" wire:navigate class="transition hover:text-[#1d4f45]">Info</a>
                <a href="{{ route('ppdb.statistik') }}" wire:navigate
                    class="transition hover:text-[#1d4f45]">Statistik</a>
                <a href="{{ route('ppdb.daftar') }}" wire:navigate class="transition hover:text-[#1d4f45]">Daftar</a>
                <a href="{{ route('login') }}" class="transition hover:text-[#1d4f45]">Admin</a>
            </div>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>
    </div>

    <div id="toast-container"
        class="pointer-events-none fixed inset-x-0 top-4 z-50 mx-auto flex w-full max-w-sm flex-col gap-3 px-4"></div>

    <script>
        window.addEventListener('toast', event => {
            const detail = event.detail?.[0] ?? event.detail ?? {};
            const toast = document.createElement('div');
            const palette = detail.type === 'success' ?
                'border-emerald-200 bg-emerald-50 text-emerald-800' :
                'border-rose-200 bg-rose-50 text-rose-800';

            toast.className =
                `pointer-events-auto rounded-2xl border px-4 py-3 text-sm font-semibold shadow-lg ${palette}`;
            toast.textContent = detail.message ?? 'Informasi terbaru tersedia.';
            document.getElementById('toast-container')?.appendChild(toast);

            window.setTimeout(() => {
                toast.remove();
            }, 3200);
        });
    </script>
</body>

</html>
