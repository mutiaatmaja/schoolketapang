<?php

use Livewire\Component;

new class extends Component {
    public array $items = [['nomor' => 'SPMB-2026-0030', 'nama' => 'Rara Maulida', 'peringkat' => 1], ['nomor' => 'SPMB-2026-0032', 'nama' => 'Rafi Akbar', 'peringkat' => 2], ['nomor' => 'SPMB-2026-0038', 'nama' => 'Hasna Anindya', 'peringkat' => 3]];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Admin SPMB</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Peserta Cadangan</h1>
        <p class="mt-2 text-sm text-slate-600">Daftar peserta dalam status cadangan berdasarkan peringkat.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[520px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-2 py-2 font-medium">Nomor</th>
                        <th class="px-2 py-2 font-medium">Nama</th>
                        <th class="px-2 py-2 font-medium">Peringkat</th>
                        <th class="px-2 py-2 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr wire:key="cadangan-{{ $item['nomor'] }}" class="border-b border-slate-100">
                            <td class="px-2 py-3">{{ $item['nomor'] }}</td>
                            <td class="px-2 py-3">{{ $item['nama'] }}</td>
                            <td class="px-2 py-3">#{{ $item['peringkat'] }}</td>
                            <td class="px-2 py-3"><span
                                    class="rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-800">Cadangan</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
