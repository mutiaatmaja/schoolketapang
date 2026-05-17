<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public array $berita = [['judul' => 'SPMB Tahun Ajaran 2026/2027 Resmi Dibuka', 'tanggal' => '15 Mei 2026', 'status' => 'Terbit'], ['judul' => 'Seminar Parenting Bulan Mei', 'tanggal' => '10 Mei 2026', 'status' => 'Terbit'], ['judul' => 'Jadwal Ujian Akhir Semester', 'tanggal' => '03 Mei 2026', 'status' => 'Draft'], ['judul' => 'Pengumuman Libur Nasional', 'tanggal' => '30 April 2026', 'status' => 'Terbit']];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Berita</h1>
        <p class="mt-2 text-sm text-slate-600">Daftar berita yang tampil pada website publik sekolah (data hardcode
            sementara).</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800">Jumlah Berita</h2>
            <span class="rounded-xl bg-sky-100 px-3 py-1 text-sm font-bold text-sky-700">{{ count($berita) }} item</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[620px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="py-2 pr-3 font-medium">Judul</th>
                        <th class="py-2 pr-3 font-medium">Tanggal</th>
                        <th class="py-2 pr-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($berita as $item)
                        <tr wire:key="berita-{{ md5($item['judul']) }}" class="border-b border-slate-100">
                            <td class="py-3 pr-3 font-semibold text-slate-700">{{ $item['judul'] }}</td>
                            <td class="py-3 pr-3 text-slate-600">{{ $item['tanggal'] }}</td>
                            <td class="py-3 pr-3">
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-semibold {{ $item['status'] === 'Terbit' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Tautan Cepat</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.publik.info-sekolah') }}" wire:navigate
                class="rounded-xl bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700">Info Sekolah</a>
            <a href="{{ route('admin.publik.visi-misi') }}" wire:navigate
                class="rounded-xl bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-700">Visi Misi</a>
            <a href="{{ route('admin.publik.prestasi') }}" wire:navigate
                class="rounded-xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Prestasi</a>
        </div>
    </section>
</div>
