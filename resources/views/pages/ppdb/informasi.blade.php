<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::ppdb.app')] class extends Component {
    public array $requirements = ['Data calon siswa: nama lengkap, tempat lahir, tanggal lahir, NIK, jenis kelamin, agama.', 'Data orang tua: nama ayah, nama ibu, nomor HP yang aktif, dan alamat domisili.', 'Unggah Akte Lahir atau surat keterangan kelahiran.', 'Unggah Kartu Keluarga terbaru.', 'Unggah foto siswa latar merah.', 'Ijazah TK bersifat opsional jika sudah tersedia.'];

    public array $steps = ['Isi data calon siswa.', 'Lengkapi data orang tua dan alamat.', 'Unggah dokumen, periksa ulang, lalu kirim pendaftaran.'];
};
?>

<div class="space-y-6">
    <section class="rounded-[28px] bg-[#1d4f45] px-5 py-6 text-white shadow-lg shadow-[#1d4f45]/20">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-white/75">SPMB SD Ketapang</p>
        <h1 class="mt-3 text-[1.9rem] font-bold leading-tight">Pendaftaran siswa baru yang ringkas dan mudah diisi lewat
            HP.</h1>
        <p class="mt-3 text-sm leading-6 text-white/80">Form dibuat bertahap agar orang tua bisa fokus satu bagian dalam
            satu waktu. Siapkan dokumen dasar sebelum mulai.</p>
        <div class="mt-5 flex flex-col gap-3">
            <a href="{{ route('ppdb.daftar') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-2xl bg-[#f6c453] px-4 py-3 text-sm font-semibold text-[#18352f] transition hover:bg-[#f0ba3a]">
                Mulai Pendaftaran
            </a>
            <p class="text-xs text-white/70">Tidak perlu memilih kelas, NIS, atau NISN saat daftar awal.</p>
        </div>
    </section>

    <section class="grid gap-4">
        <div class="rounded-[24px] border border-[#d8e4df] bg-white p-5 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Yang perlu disiapkan</h2>
            <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                @foreach ($requirements as $requirement)
                    <li wire:key="{{ $requirement }}" class="flex gap-3">
                        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-[#1d4f45]"></span>
                        <span>{{ $requirement }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="rounded-[24px] border border-[#eadfca] bg-[#fff9ef] p-5 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Alur pendaftaran</h2>
            <div class="mt-4 space-y-3">
                @foreach ($steps as $index => $step)
                    <div wire:key="step-{{ $index }}" class="flex items-start gap-3">
                        <span
                            class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#18352f] text-xs font-semibold text-white">
                            {{ $index + 1 }}
                        </span>
                        <p class="pt-0.5 text-sm leading-6 text-slate-700">{{ $step }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
