<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Akun Orang Tua - {{ config('app.name', 'Elementary School') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600;700&family=Nunito+Sans:wght@400;700&display=swap"
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
                        background: '#f7f9fb',
                        'on-surface-variant': '#444653',
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

<body class="bg-slate-50 font-body text-slate-900 antialiased">
    <div class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-secondary">SPMB Online</p>
                <h1 class="mt-3 font-headline text-2xl font-bold text-primary">Buat akun orang tua</h1>
                <p class="mt-2 text-sm leading-6 text-on-surface-variant">
                    Setelah akun dibuat, Anda langsung bisa masuk dan melengkapi data calon siswa.
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

            <form action="{{ route('ppdb.register.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama orang tua</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10"
                        placeholder="Nama lengkap orang tua">
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email"
                        required
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10"
                        placeholder="nama@email.com">
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Kata sandi</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10"
                        placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label for="password_confirmation"
                        class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi kata sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        autocomplete="new-password" required
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10"
                        placeholder="Ulangi kata sandi">
                </div>

                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:bg-primary-container">
                    Buat akun dan lanjutkan
                </button>
            </form>

            <div class="mt-6 flex items-center justify-between text-sm">
                <a href="{{ route('ppdb.informasi') }}" class="text-primary hover:underline">Info SPMB</a>
                <a href="{{ route('login') }}" class="text-primary hover:underline">Sudah punya akun?</a>
            </div>
        </div>
    </div>
</body>

</html>
