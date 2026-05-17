<?php

use Livewire\Component;

new class extends Component {
    public array $items = [['nomor' => 'SPMB-2026-0019', 'nama' => 'Alif Rahman', 'catatan' => 'Dokumen tidak lengkap'], ['nomor' => 'SPMB-2026-0027', 'nama' => 'Meysa Oktavia', 'catatan' => 'Nilai seleksi di bawah ambang batas'], ['nomor' => 'SPMB-2026-0034', 'nama' => 'Arsyila Putri', 'catatan' => 'Usia tidak sesuai ketentuan']];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">Admin SPMB</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Peserta Ditolak</h1>
        <p class="mt-2 text-sm text-slate-600">Daftar peserta yang tidak lolos seleksi beserta catatan singkat.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-2 py-2 font-medium">Nomor</th>
                        <th class="px-2 py-2 font-medium">Nama</th>
                        <th class="px-2 py-2 font-medium">Catatan</th>
                        <th class="px-2 py-2 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr wire:key="ditolak-{{ $item['nomor'] }}" class="border-b border-slate-100">
                            <td class="px-2 py-3">{{ $item['nomor'] }}</td>
                            <td class="px-2 py-3">{{ $item['nama'] }}</td>
                            <td class="px-2 py-3">{{ $item['catatan'] }}</td>
                            <td class="px-2 py-3"><span
                                    class="rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-800">Ditolak</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
