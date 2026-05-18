<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pendaftaran {{ $registration->registration_number }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    class="min-h-screen bg-[radial-gradient(circle_at_top,#f6c453_0%,#f5efe2_28%,#eef4f1_60%,#eef4f1_100%)] font-sans text-slate-900">
    <main class="mx-auto w-full max-w-md px-4 py-5 sm:max-w-lg">
        <section class="rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#1d4f45]">Detail Pendaftaran SPMB</p>
            <h1 class="mt-2 text-2xl font-bold leading-tight text-slate-900">{{ $registration->name }}</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Nomor pendaftaran: {{ $registration->registration_number }}
            </p>
            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                Status saat ini: {{ str($registration->status)->headline() }}
            </div>
            <div class="mt-4 flex flex-col gap-3">
                <a href="{{ route('ppdb.rekap-pdf', ['registrationNumber' => $registration->registration_number]) }}"
                    target="_blank" rel="noopener"
                    class="inline-flex items-center justify-center rounded-2xl bg-[#18352f] px-4 py-3 text-sm font-semibold text-white">
                    Buka PDF Rekap
                </a>
                <a href="{{ route('ppdb.informasi') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700">
                    Kembali ke informasi SPMB
                </a>
            </div>
        </section>

        <section class="mt-4 rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-base font-bold text-slate-900">Data Calon Siswa</h2>
            <div class="mt-4 space-y-3 text-sm">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">NIK</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $registration->nik }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">No. KK</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $registration->family_card_number }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tempat, Tanggal Lahir</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $registration->birth_place }},
                        {{ $registration->birth_date?->format('d M Y') ?? '-' }}</p>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jenis Kelamin</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $registration->gender }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Agama</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $registration->religion }}</p>
                    </div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat</p>
                    <p class="mt-1 leading-6 text-slate-900">{{ $registration->address }}</p>
                </div>
            </div>
        </section>

        <section class="mt-4 rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-base font-bold text-slate-900">Data Orang Tua</h2>
            <div class="mt-4 space-y-3 text-sm">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ayah / Wali</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $registration->father_name }}</p>
                    <p class="mt-1 text-slate-600">{{ $registration->father_occupation ?: 'Pekerjaan belum diisi' }}
                    </p>
                    <p class="mt-1 text-slate-900">{{ $registration->father_phone }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ibu</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $registration->mother_name }}</p>
                    <p class="mt-1 text-slate-600">{{ $registration->mother_occupation ?: 'Pekerjaan belum diisi' }}
                    </p>
                    <p class="mt-1 text-slate-900">{{ $registration->mother_phone ?: 'Nomor HP belum diisi' }}</p>
                </div>
            </div>
        </section>

        <section class="mt-4 rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-base font-bold text-slate-900">Status Berkas</h2>
            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-semibold text-slate-900">Akte Lahir</p>
                    <p
                        class="mt-2 text-xs font-medium {{ $registration->birth_certificate_path ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $registration->birth_certificate_path ? 'Sudah diunggah' : 'Belum diunggah' }}
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-semibold text-slate-900">Kartu Keluarga</p>
                    <p
                        class="mt-2 text-xs font-medium {{ $registration->family_card_path ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $registration->family_card_path ? 'Sudah diunggah' : 'Belum diunggah' }}
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-semibold text-slate-900">Foto Siswa</p>
                    <p
                        class="mt-2 text-xs font-medium {{ $registration->student_photo_path ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $registration->student_photo_path ? 'Sudah diunggah' : 'Belum diunggah' }}
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-semibold text-slate-900">Ijazah TK</p>
                    <p
                        class="mt-2 text-xs font-medium {{ $registration->kindergarten_certificate_path ? 'text-emerald-700' : 'text-slate-600' }}">
                        {{ $registration->kindergarten_certificate_path ? 'Sudah diunggah' : 'Tidak dilampirkan' }}
                    </p>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
