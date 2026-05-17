<?php

use Livewire\Component;

new class extends Component {
    public int $step = 1;

    public string $nama_lengkap = '';
    public string $nik = '';
    public string $nama_ayah = '';
    public string $nama_ibu = '';

    public function nextStep(): void
    {
        $this->validate($this->rulesForStep($this->step));

        if ($this->step < 3) {
            $this->step++;
        }
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function submitForm(): void
    {
        $this->validate($this->rulesForStep(2));

        $this->dispatch('toast', type: 'success', message: 'Pendaftaran berhasil dikirim.');
        $this->step = 3;
    }

    protected function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'nama_lengkap' => ['required', 'string', 'min:3'],
                'nik' => ['required', 'digits:16'],
            ],
            2 => [
                'nama_ayah' => ['required', 'string', 'min:3'],
                'nama_ibu' => ['required', 'string', 'min:3'],
            ],
            default => [],
        };
    }
};
?>

<div class="space-y-4 p-4 md:p-8">
    <header class="space-y-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Form PPDB</p>
        <h1 class="text-2xl font-bold text-slate-800">Pendaftaran Siswa Baru</h1>
        <p class="text-sm text-slate-600">Isi data secara bertahap agar lebih mudah saat menggunakan HP.</p>
    </header>

    <div class="h-2 w-full rounded-full bg-slate-200">
        <div class="h-2 rounded-full bg-sky-600 transition-all"
            style="width: {{ $step === 1 ? '33%' : ($step === 2 ? '66%' : '100%') }}"></div>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        @if ($step === 1)
            <div class="space-y-3">
                <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <input type="text" wire:model.live.blur="nama_lengkap"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                @error('nama_lengkap')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror

                <label class="block text-sm font-medium text-slate-700">NIK</label>
                <input type="text" wire:model.live.blur="nik"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                @error('nik')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        @elseif ($step === 2)
            <div class="space-y-3">
                <label class="block text-sm font-medium text-slate-700">Nama Ayah</label>
                <input type="text" wire:model.live.blur="nama_ayah"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                @error('nama_ayah')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror

                <label class="block text-sm font-medium text-slate-700">Nama Ibu</label>
                <input type="text" wire:model.live.blur="nama_ibu"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                @error('nama_ibu')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        @else
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                Data pendaftaran berhasil dikirim. Tim kami akan memverifikasi data Anda.
            </div>
        @endif

        <div class="mt-5 flex gap-2">
            @if ($step > 1 && $step < 3)
                <button type="button" wire:click="previousStep"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
                    Kembali
                </button>
            @endif

            @if ($step === 1)
                <button type="button" wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep"
                    class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                    <span wire:loading.remove wire:target="nextStep">Lanjut</span>
                    <span wire:loading wire:target="nextStep">Memproses data...</span>
                </button>
            @elseif ($step === 2)
                <button type="button" wire:click="submitForm" wire:loading.attr="disabled" wire:target="submitForm"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60">
                    <span wire:loading.remove wire:target="submitForm">Kirim Pendaftaran</span>
                    <span wire:loading wire:target="submitForm">Memproses pendaftaran...</span>
                </button>
            @endif
        </div>
    </section>
</div>
