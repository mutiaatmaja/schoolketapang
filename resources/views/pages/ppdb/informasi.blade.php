<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::ppdb.app')] class extends Component {
    public array $steps = [['title' => 'Buat akun orang tua', 'description' => 'Daftar dengan email dan kata sandi untuk menyimpan proses pendaftaran.'], ['title' => 'Data calon siswa', 'description' => 'Isi identitas utama calon murid tanpa kelas, NIS, atau NISN.'], ['title' => 'Upload berkas', 'description' => 'Siapkan Akte, KK, foto siswa, dan ijazah TK bila ada.'], ['title' => 'Review dan kirim', 'description' => 'Periksa ulang semua data sebelum pendaftaran dikirim.']];

    public array $requirements = [['label' => 'Akte Lahir', 'description' => 'Wajib. Format JPG, PNG, atau PDF.'], ['label' => 'Kartu Keluarga', 'description' => 'Wajib. Format JPG, PNG, atau PDF.'], ['label' => 'Foto siswa latar merah', 'description' => 'Wajib. Format JPG atau PNG.'], ['label' => 'Ijazah TK', 'description' => 'Opsional. Upload bila tersedia.']];
};
?>

<div class="space-y-5">
    <section class="rounded-[28px] bg-sky-700 px-5 py-6 text-white shadow-lg shadow-sky-200/70">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-100">SPMB Online</p>
        <h1 class="mt-3 text-2xl font-bold leading-tight">Pendaftaran murid baru yang lebih ringkas untuk layar HP.</h1>
        <p class="mt-3 text-sm leading-6 text-sky-50/90">Form dibuat bertahap agar orang tua bisa fokus mengisi data satu
            per satu tanpa layar terasa penuh.</p>
        <p class="mt-3 text-sm leading-6 text-sky-50/90">Alur dimulai dari akun orang tua, lalu data calon siswa diisi
            setelah login agar proses bisa dilanjutkan kapan saja.</p>
        @auth
            <a href="{{ route('ppdb.daftar') }}" wire:navigate
                class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-sky-700 shadow-sm">
                Lanjutkan pendaftaran
            </a>
        @else
            <div class="mt-5 flex flex-col gap-3">
                <a href="{{ route('ppdb.register') }}"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-sky-700 shadow-sm">
                    Buat akun orang tua
                </a>
                <a href="{{ route('login') }}"
                    class="inline-flex w-full items-center justify-center rounded-2xl border border-white/30 bg-sky-800/40 px-4 py-3 text-sm font-semibold text-white">
                    Masuk ke akun
                </a>
            </div>
        @endauth
    </section>

    <section class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Alur pendaftaran</h2>
                <p class="mt-1 text-sm text-slate-500">Empat langkah inti sebelum data masuk ke tim verifikasi.</p>
            </div>
            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">4 Step</span>
        </div>

        <div class="mt-4 space-y-3">
            @foreach ($steps as $index => $step)
                <article wire:key="spmb-step-{{ $step['title'] }}" class="flex gap-3 rounded-2xl bg-slate-50 p-4">
                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sky-600 text-sm font-bold text-white">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">{{ $step['title'] }}</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">{{ $step['description'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Berkas yang perlu disiapkan</h2>
        <div class="mt-4 space-y-3">
            @foreach ($requirements as $requirement)
                <article wire:key="requirement-{{ $requirement['label'] }}"
                    class="rounded-2xl border border-slate-200 p-4">
                    <p class="font-semibold text-slate-800">{{ $requirement['label'] }}</p>
                    <p class="mt-1 text-sm leading-6 text-slate-500">{{ $requirement['description'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
</div>
