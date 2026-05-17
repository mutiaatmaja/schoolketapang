<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public array $classes = [['nama' => '1A', 'wali' => 'Ibu Ratna', 'jumlah_siswa' => 32], ['nama' => '3B', 'wali' => 'Bapak Arif', 'jumlah_siswa' => 36], ['nama' => '6A', 'wali' => 'Ibu Dina', 'jumlah_siswa' => 34]];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Akademik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Data Kelas</h1>
        <p class="mt-2 text-sm text-slate-600">Daftar kelas dan wali kelas aktif (hardcode sementara).</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[620px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="py-2 pr-3 font-medium">Kelas</th>
                        <th class="py-2 pr-3 font-medium">Wali Kelas</th>
                        <th class="py-2 pr-3 font-medium">Jumlah Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $class)
                        <tr wire:key="kelas-{{ $class['nama'] }}" class="border-b border-slate-100">
                            <td class="py-3 pr-3 font-semibold text-slate-700">{{ $class['nama'] }}</td>
                            <td class="py-3 pr-3">{{ $class['wali'] }}</td>
                            <td class="py-3 pr-3">{{ $class['jumlah_siswa'] }} siswa</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
