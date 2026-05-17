<?php

use Livewire\Component;

new class extends Component {
    public array $items = [['nomor' => 'SPMB-2026-0003', 'nama' => 'Naila Putri', 'kelas' => '1A'], ['nomor' => 'SPMB-2026-0011', 'nama' => 'Farhan Rizki', 'kelas' => '1B'], ['nomor' => 'SPMB-2026-0020', 'nama' => 'Qanita Salsabila', 'kelas' => '1C']];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Admin SPMB</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Peserta Lulus</h1>
        <p class="mt-2 text-sm text-slate-600">Daftar peserta yang dinyatakan lulus seleksi.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[520px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-2 py-2 font-medium">Nomor</th>
                        <th class="px-2 py-2 font-medium">Nama</th>
                        <th class="px-2 py-2 font-medium">Rencana Kelas</th>
                        <th class="px-2 py-2 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr wire:key="lulus-{{ $item['nomor'] }}" class="border-b border-slate-100">
                            <td class="px-2 py-3">{{ $item['nomor'] }}</td>
                            <td class="px-2 py-3">{{ $item['nama'] }}</td>
                            <td class="px-2 py-3">{{ $item['kelas'] }}</td>
                            <td class="px-2 py-3"><span
                                    class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">Lulus</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
