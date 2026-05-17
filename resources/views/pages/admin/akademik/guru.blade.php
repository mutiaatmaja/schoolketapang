<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public array $teachers = [['nama' => 'Ibu Rahmawati', 'mapel' => 'Matematika', 'status' => 'Tetap'], ['nama' => 'Bapak Dedi Kurniawan', 'mapel' => 'Bahasa Indonesia', 'status' => 'Tetap'], ['nama' => 'Ibu Sinta Lestari', 'mapel' => 'IPA', 'status' => 'Kontrak']];
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Akademik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Data Guru</h1>
        <p class="mt-2 text-sm text-slate-600">Daftar guru dan mata pelajaran utama (hardcode sementara).</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[620px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="py-2 pr-3 font-medium">Nama Guru</th>
                        <th class="py-2 pr-3 font-medium">Mata Pelajaran</th>
                        <th class="py-2 pr-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr wire:key="guru-{{ md5($teacher['nama']) }}" class="border-b border-slate-100">
                            <td class="py-3 pr-3 font-semibold text-slate-700">{{ $teacher['nama'] }}</td>
                            <td class="py-3 pr-3">{{ $teacher['mapel'] }}</td>
                            <td class="py-3 pr-3">
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-semibold {{ $teacher['status'] === 'Tetap' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $teacher['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
