<?php

use App\Models\SpmbRegistration;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    public function getItemsProperty(): Collection
    {
        return SpmbRegistration::query()
            ->where('status', 'submitted')
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery
                        ->where('registration_number', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('submitted_at')
            ->get();
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">Admin SPMB</p>
                <h1 class="mt-2 text-2xl font-bold text-slate-800">Peserta Belum Validasi</h1>
                <p class="mt-2 text-sm text-slate-600">Daftar peserta yang menunggu verifikasi dokumen.</p>
            </div>
            <a href="{{ route('admin.ppdb.export', ['status' => 'submitted']) }}"
                class="mt-1 inline-flex shrink-0 items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Export Excel
            </a>
        </div>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="relative mb-4 max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor, nama, atau NIK"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 text-sm">
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Cari...</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-2 py-2 font-medium">Nomor</th>
                        <th class="px-2 py-2 font-medium">Nama</th>
                        <th class="px-2 py-2 font-medium">NIK</th>
                        <th class="px-2 py-2 font-medium">Umur</th>
                        <th class="px-2 py-2 font-medium">Status</th>
                        <th class="px-2 py-2 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->items as $item)
                        <tr wire:key="belum-validasi-{{ $item->registration_number }}"
                            class="border-b border-slate-100">
                            <td class="px-2 py-3">{{ $item->registration_number }}</td>
                            <td class="px-2 py-3">{{ $item->name }}</td>
                            <td class="px-2 py-3">{{ $item->nik }}</td>
                            <td class="px-2 py-3">{{ $item->ageAtRegistrationLabel() }}</td>
                            <td class="px-2 py-3"><span
                                    class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">Menunggu
                                    Validasi</span></td>
                            <td class="px-2 py-3">
                                <a href="{{ route('admin.ppdb.pendaftar.detail', ['registration' => $item->registration_number]) }}"
                                    wire:navigate
                                    class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-2 py-8 text-center text-sm text-slate-500">Tidak ada peserta
                                belum validasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
