<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public array $prestasi = [['nama' => 'Juara 1 Olimpiade Sains Tingkat Kabupaten', 'tahun' => '2026', 'tingkat' => 'Kabupaten'], ['nama' => 'Medali Emas Karate Junior', 'tahun' => '2026', 'tingkat' => 'Provinsi'], ['nama' => 'Juara 2 Lomba Cerdas Cermat', 'tahun' => '2025', 'tingkat' => 'Kota'], ['nama' => 'Best Performance Festival Tari Anak', 'tahun' => '2025', 'tingkat' => 'Nasional']];

    public int $jumlahPesan = 31;
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Prestasi</h1>
        <p class="mt-2 text-sm text-slate-600">Ringkasan prestasi siswa dan statistik pesan publik (hardcode sementara).
        </p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800">Jumlah Prestasi</h2>
            <span class="rounded-xl bg-emerald-100 px-3 py-1 text-sm font-bold text-emerald-700">{{ count($prestasi) }}
                item</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[620px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="py-2 pr-3 font-medium">Nama Prestasi</th>
                        <th class="py-2 pr-3 font-medium">Tahun</th>
                        <th class="py-2 pr-3 font-medium">Tingkat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prestasi as $item)
                        <tr wire:key="prestasi-{{ md5($item['nama']) }}" class="border-b border-slate-100">
                            <td class="py-3 pr-3 font-semibold text-slate-700">{{ $item['nama'] }}</td>
                            <td class="py-3 pr-3 text-slate-600">{{ $item['tahun'] }}</td>
                            <td class="py-3 pr-3 text-slate-600">{{ $item['tingkat'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800">Jumlah Pesan</h2>
            <span class="rounded-xl bg-amber-100 px-3 py-1 text-sm font-bold text-amber-700">{{ $jumlahPesan }}
                pesan</span>
        </div>
        <p class="mt-2 text-sm text-slate-600">Total pesan dari formulir kontak website sekolah.</p>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Tautan Cepat</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.publik.info-sekolah') }}" wire:navigate
                class="rounded-xl bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700">Info Sekolah</a>
            <a href="{{ route('admin.publik.visi-misi') }}" wire:navigate
                class="rounded-xl bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-700">Visi Misi</a>
            <a href="{{ route('admin.publik.berita') }}" wire:navigate
                class="rounded-xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Berita</a>
        </div>
    </section>
</div>
