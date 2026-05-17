<?php

use Livewire\Component;

new class extends Component {
    public array $requirements = ['Fotokopi Kartu Keluarga', 'Fotokopi Akta Kelahiran', 'Fotokopi KTP orang tua', 'Pas foto calon siswa'];
};
?>

<div class="space-y-4 p-4 md:p-8">
    <header class="space-y-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">SPMB Online (Seleksi Penerimaan Murid Baru)
        </p>
        <h1 class="text-2xl font-bold text-slate-800">Informasi Pendaftaran</h1>
        <p class="text-sm text-slate-600">Silakan pelajari jadwal, syarat, dan alur pendaftaran sebelum mengisi formulir.
        </p>
    </header>

    <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="text-base font-semibold text-slate-800">Persyaratan</h2>
        <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-slate-600">
            @foreach ($requirements as $requirement)
                <li wire:key="{{ $requirement }}">{{ $requirement }}</li>
            @endforeach
        </ul>
    </section>

    <a href="{{ route('ppdb.daftar') }}" wire:navigate
        class="inline-flex w-full items-center justify-center rounded-xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white sm:w-auto">
        Lanjut Isi Formulir
    </a>
</div>
