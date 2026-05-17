<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Masuk - {{ config('app.name', 'Elementary School') }}</title>
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
                    boxShadow: {
                        soft: '0 20px 60px rgba(0, 40, 142, 0.16)',
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

        .bg-auth-pattern {
            background-image: radial-gradient(circle at top left, rgba(255, 195, 41, 0.22), transparent 30%), radial-gradient(circle at bottom right, rgba(0, 40, 142, 0.18), transparent 28%), linear-gradient(135deg, #f7f9fb 0%, #eef4ff 100%);
        }
    </style>
</head>

<body class="bg-slate-50 font-body text-on-surface antialiased">
    <div class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-secondary">Login Admin</p>
                <h1 class="mt-3 font-headline text-2xl font-bold text-primary">Masuk ke akun Anda</h1>
                <p class="mt-2 text-sm leading-6 text-on-surface-variant">
                    Gunakan email dan kata sandi yang terdaftar.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="font-bold">Terjadi kesalahan:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email"
                        required autofocus
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10"
                        placeholder="nama@email.com">
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Kata Sandi</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10"
                        placeholder="Masukkan kata sandi">
                </div>

                <label class="flex items-center gap-3 text-sm text-slate-600">
                    <input type="checkbox" name="remember" value="1"
                        class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary">
                    Ingat saya
                </label>

                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:bg-primary-container">
                    Masuk
                </button>
            </form>

            <div class="mt-6 flex items-center justify-between text-sm">
                <a href="{{ route('depan.beranda') }}" class="text-primary hover:underline">Beranda</a>
                <a href="{{ route('ppdb.informasi') }}" class="text-primary hover:underline">PPDB</a>
            </div>
        </div>
    </div>
</body>

</html>
