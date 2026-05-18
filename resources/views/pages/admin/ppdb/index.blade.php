<?php

use App\Models\SpmbRegistration;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    public function getStatsProperty(): array
    {
        return [['label' => 'Total Pendaftar', 'value' => SpmbRegistration::query()->count(), 'route' => 'admin.ppdb.pendaftar'], ['label' => 'Akun Orang Tua', 'value' => $this->parentAccountsCount(), 'route' => 'admin.ppdb.orang-tua'], ['label' => 'Belum Validasi', 'value' => SpmbRegistration::query()->where('status', 'submitted')->count(), 'route' => 'admin.ppdb.belum-validasi'], ['label' => 'Peserta Lulus', 'value' => SpmbRegistration::query()->where('status', 'verified')->count(), 'route' => 'admin.ppdb.lulus'], ['label' => 'Peserta Ditolak', 'value' => SpmbRegistration::query()->where('status', 'rejected')->count(), 'route' => 'admin.ppdb.ditolak']];
    }

    public function getLatestRegistrationsProperty(): Collection
    {
        return SpmbRegistration::query()
            ->with(['user:id,name,email'])
            ->latest('submitted_at')
            ->latest('id')
            ->limit(5)
            ->get();
    }

    private function parentAccountsCount(): int
    {
        return User::query()
            ->whereHas('roles', function ($query): void {
                $query->where('name', 'orang_tua');
            })
            ->count();
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin SPMB</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Ringkasan SPMB</h1>
        <p class="mt-2 text-sm text-slate-600">Pantau jumlah pendaftar, akun orang tua, dan pengajuan terbaru dari satu
            halaman.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Statistik Seleksi</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($this->stats as $card)
                <article wire:key="ppdb-stat-{{ $card['label'] }}" class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $card['value'] }}</p>
                    <a href="{{ route($card['route']) }}" wire:navigate
                        class="mt-3 inline-flex text-sm font-semibold text-sky-700 hover:underline">Buka detail</a>
                </article>
            @endforeach
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Pendaftaran Terbaru</h2>
                <p class="mt-1 text-sm text-slate-500">Lima pengajuan terakhir beserta akun orang tua yang mengirim.</p>
            </div>
            <a href="{{ route('admin.ppdb.pendaftar') }}" wire:navigate
                class="text-sm font-semibold text-sky-700 hover:underline">Lihat semua peserta</a>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="border-b border-slate-200 text-slate-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Nomor</th>
                        <th class="px-4 py-3 font-medium">Nama Anak</th>
                        <th class="px-4 py-3 font-medium">Akun Orang Tua</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->latestRegistrations as $registration)
                        <tr wire:key="ppdb-latest-{{ $registration->id }}" class="border-b border-slate-100">
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $registration->registration_number }}
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $registration->name }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                <p>{{ $registration->user?->name ?? '-' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $registration->user?->email ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $registration->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada pengajuan SPMB.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
