<?php

use App\Models\SpmbRegistration;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    public function getItemsProperty(): Collection
    {
        return SpmbRegistration::query()
            ->where('status', 'ditolak')
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($innerQuery): void {
                    $innerQuery
                        ->where('registration_number', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('validation_note', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('validated_at')
            ->orderByDesc('submitted_at')
            ->get();
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">Admin SPMB</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Peserta Ditolak</h1>
        <p class="mt-2 text-sm text-slate-600">Daftar peserta yang tidak lolos seleksi beserta catatan singkat.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="relative mb-4 max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor, nama, NIK, atau catatan"
                class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 text-sm">
            <span wire:loading wire:target="search"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Cari...</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-2 py-2 font-medium">Nomor</th>
                        <th class="px-2 py-2 font-medium">Nama</th>
                        <th class="px-2 py-2 font-medium">NIK</th>
                        <th class="px-2 py-2 font-medium">Catatan</th>
                        <th class="px-2 py-2 font-medium">Status</th>
                        <th class="px-2 py-2 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->items as $item)
                        <tr wire:key="ditolak-{{ $item->registration_number }}" class="border-b border-slate-100">
                            <td class="px-2 py-3">{{ $item->registration_number }}</td>
                            <td class="px-2 py-3">{{ $item->name }}</td>
                            <td class="px-2 py-3">{{ $item->nik }}</td>
                            <td class="px-2 py-3">{{ $item->validation_note ?: '-' }}</td>
                            <td class="px-2 py-3"><span
                                    class="rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-800">Ditolak</span>
                            </td>
                            <td class="px-2 py-3">
                                <a href="{{ route('admin.ppdb.pendaftar.detail', ['registration' => $item->registration_number]) }}"
                                    wire:navigate
                                    class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-2 py-8 text-center text-sm text-slate-500">Tidak ada peserta
                                ditolak.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
