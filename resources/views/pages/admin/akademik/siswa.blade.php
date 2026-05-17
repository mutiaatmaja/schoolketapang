<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public array $students = [['nis' => 'S-2026-001', 'nama' => 'Alya Safira', 'kelas' => '6A', 'status' => 'Aktif'], ['nis' => 'S-2026-002', 'nama' => 'Raka Pratama', 'kelas' => '5B', 'status' => 'Aktif'], ['nis' => 'S-2026-003', 'nama' => 'Naila Putri', 'kelas' => '4A', 'status' => 'Aktif']];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Akademik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Data Siswa</h1>
        <p class="mt-2 text-sm text-slate-600">Daftar siswa untuk kebutuhan monitoring akademik (hardcode sementara).</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[620px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="py-2 pr-3 font-medium">NIS</th>
                        <th class="py-2 pr-3 font-medium">Nama</th>
                        <th class="py-2 pr-3 font-medium">Kelas</th>
                        <th class="py-2 pr-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr wire:key="siswa-{{ $student['nis'] }}" class="border-b border-slate-100">
                            <td class="py-3 pr-3">{{ $student['nis'] }}</td>
                            <td class="py-3 pr-3 font-semibold text-slate-700">{{ $student['nama'] }}</td>
                            <td class="py-3 pr-3">{{ $student['kelas'] }}</td>
                            <td class="py-3 pr-3">
                                <span
                                    class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">{{ $student['status'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
