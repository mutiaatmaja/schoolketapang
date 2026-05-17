<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    public array $applicants = [['id' => 1, 'nomor' => 'SPMB-2026-0001', 'nama' => 'Alya Safira', 'status' => 'submitted'], ['id' => 2, 'nomor' => 'SPMB-2026-0002', 'nama' => 'Raka Pratama', 'status' => 'under_review'], ['id' => 3, 'nomor' => 'SPMB-2026-0003', 'nama' => 'Naila Putri', 'status' => 'verified']];

    public function getFilteredApplicantsProperty(): array
    {
        if ($this->search === '') {
            return $this->applicants;
        }

        return array_values(
            array_filter($this->applicants, function (array $applicant): bool {
                $needle = mb_strtolower($this->search);
                return str_contains(mb_strtolower($applicant['nama']), $needle) || str_contains(mb_strtolower($applicant['nomor']), $needle);
            }),
        );
    }

    public function verifyApplicant(int $id): void
    {
        $this->dispatch('toast', type: 'success', message: "Data pendaftar #{$id} berhasil diverifikasi.");
    }
};
?>

<div class="space-y-4 p-4 md:p-8">
    <header class="space-y-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin SPMB</p>
        <h1 class="text-2xl font-bold text-slate-800">Data Pendaftar</h1>
    </header>

    <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau nomor pendaftaran"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 pr-10 text-sm" />
            <span wire:loading wire:target="search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">Mencari...</span>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-2 py-2 font-medium">Nomor</th>
                        <th class="px-2 py-2 font-medium">Nama</th>
                        <th class="px-2 py-2 font-medium">Status</th>
                        <th class="px-2 py-2 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->filteredApplicants as $applicant)
                        <tr class="border-b border-slate-100" wire:key="pendaftar-{{ $applicant['id'] }}">
                            <td class="px-2 py-3">{{ $applicant['nomor'] }}</td>
                            <td class="px-2 py-3">{{ $applicant['nama'] }}</td>
                            <td class="px-2 py-3">
                                <span
                                    class="rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">{{ $applicant['status'] }}</span>
                            </td>
                            <td class="px-2 py-3">
                                <button type="button" wire:click="verifyApplicant({{ $applicant['id'] }})"
                                    wire:loading.attr="disabled" wire:target="verifyApplicant({{ $applicant['id'] }})"
                                    class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white disabled:opacity-60">
                                    <span wire:loading.remove
                                        wire:target="verifyApplicant({{ $applicant['id'] }})">Verifikasi</span>
                                    <span wire:loading
                                        wire:target="verifyApplicant({{ $applicant['id'] }})">Memproses...</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-2 py-6 text-center text-sm text-slate-500">Belum ada data
                                pendaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
