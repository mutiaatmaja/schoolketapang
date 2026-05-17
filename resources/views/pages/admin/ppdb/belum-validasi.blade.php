<?php

use Livewire\Component;

new class extends Component {
    public array $items = [['nomor' => 'SPMB-2026-0041', 'nama' => 'Nadia Azzahra', 'asal' => 'TK Melati'], ['nomor' => 'SPMB-2026-0048', 'nama' => 'Rafi Maulana', 'asal' => 'TK Kasih Bunda'], ['nomor' => 'SPMB-2026-0052', 'nama' => 'Keisha Putri', 'asal' => 'TK Harapan']];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">Admin SPMB</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Peserta Belum Validasi</h1>
        <p class="mt-2 text-sm text-slate-600">Daftar peserta yang menunggu verifikasi dokumen.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[540px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-2 py-2 font-medium">Nomor</th>
                        <th class="px-2 py-2 font-medium">Nama</th>
                        <th class="px-2 py-2 font-medium">Asal Sekolah</th>
                        <th class="px-2 py-2 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr wire:key="belum-validasi-{{ $item['nomor'] }}" class="border-b border-slate-100">
                            <td class="px-2 py-3">{{ $item['nomor'] }}</td>
                            <td class="px-2 py-3">{{ $item['nama'] }}</td>
                            <td class="px-2 py-3">{{ $item['asal'] }}</td>
                            <td class="px-2 py-3"><span
                                    class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">Menunggu
                                    Validasi</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
