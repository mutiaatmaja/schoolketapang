<?php

use App\Models\SpmbRegistration;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

new class extends Component {
    public SpmbRegistration $registration;

    public function mount(SpmbRegistration $registration): void
    {
        $this->registration = $registration;
    }
};
?>

@php
    $documentStatuses = [
        [
            'label' => 'Akte Lahir',
            'path' => $registration->birth_certificate_path,
        ],
        [
            'label' => 'Kartu Keluarga',
            'path' => $registration->family_card_path,
        ],
        [
            'label' => 'Foto Siswa',
            'path' => $registration->student_photo_path,
        ],
        [
            'label' => 'Ijazah TK',
            'path' => $registration->kindergarten_certificate_path,
        ],
    ];
@endphp

<div x-data="{ viewerOpen: false, viewerUrl: '', viewerLabel: '', viewerType: 'file' }" class="space-y-6">
    <section class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-outline-variant/15">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Detail Peserta SPMB</p>
                <h1 class="mt-2 text-3xl font-bold text-on-surface">{{ $registration->name }}</h1>
                <p class="mt-2 text-sm text-on-surface-variant">Nomor pendaftaran:
                    {{ $registration->registration_number }}</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('admin.ppdb.pendaftar') }}" wire:navigate
                    class="inline-flex items-center justify-center rounded-2xl border border-outline-variant/30 px-4 py-3 text-sm font-semibold text-on-surface">Kembali
                    ke Semua Peserta</a>
                <a href="{{ route('ppdb.rekap-pdf', ['registrationNumber' => $registration->registration_number]) }}"
                    target="_blank" rel="noopener"
                    class="inline-flex items-center justify-center rounded-2xl bg-primary px-4 py-3 text-sm font-semibold text-white">Cetak
                    Rekap PDF</a>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-3">
        <article class="rounded-[28px] border border-outline-variant/20 bg-white p-6 shadow-sm xl:col-span-2">
            <h2 class="text-lg font-bold text-on-surface">Data Peserta</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl bg-surface-container-low p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">NIK</p>
                    <p class="mt-2 text-sm font-semibold text-on-surface">{{ $registration->nik }}</p>
                </div>
                <div class="rounded-2xl bg-surface-container-low p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">No. KK</p>
                    <p class="mt-2 text-sm font-semibold text-on-surface">{{ $registration->family_card_number }}</p>
                </div>
                <div class="rounded-2xl bg-surface-container-low p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Tempat, Tanggal
                        Lahir</p>
                    <p class="mt-2 text-sm font-semibold text-on-surface">{{ $registration->birth_place }},
                        {{ $registration->birth_date?->format('d M Y') ?? '-' }}</p>
                </div>
                <div class="rounded-2xl bg-surface-container-low p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Jenis Kelamin</p>
                    <p class="mt-2 text-sm font-semibold text-on-surface">{{ $registration->gender }}</p>
                </div>
                <div class="rounded-2xl bg-surface-container-low p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Agama</p>
                    <p class="mt-2 text-sm font-semibold text-on-surface">{{ $registration->religion }}</p>
                </div>
                <div class="rounded-2xl bg-surface-container-low p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Status</p>
                    <p class="mt-2 text-sm font-semibold text-on-surface">{{ str($registration->status)->headline() }}
                    </p>
                </div>
            </div>

            <div class="mt-4 rounded-2xl bg-surface-container-low p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Alamat</p>
                <p class="mt-2 text-sm leading-6 text-on-surface">{{ $registration->address }}</p>
            </div>

            <div class="mt-4 rounded-2xl bg-surface-container-low p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Catatan</p>
                <p class="mt-2 text-sm leading-6 text-on-surface">
                    {{ $registration->notes ?: 'Tidak ada catatan tambahan.' }}</p>
            </div>
        </article>

        <article class="rounded-[28px] border border-outline-variant/20 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-on-surface">Data Orang Tua</h2>
            <div class="mt-4 space-y-4 text-sm">
                <div class="rounded-2xl bg-surface-container-low p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Ayah</p>
                    <p class="mt-2 font-semibold text-on-surface">{{ $registration->father_name }}</p>
                    <p class="mt-1 text-on-surface-variant">
                        {{ $registration->father_occupation ?: 'Pekerjaan belum diisi' }}</p>
                    <p class="mt-1 text-on-surface">{{ $registration->father_phone }}</p>
                </div>
                <div class="rounded-2xl bg-surface-container-low p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">Ibu</p>
                    <p class="mt-2 font-semibold text-on-surface">{{ $registration->mother_name }}</p>
                    <p class="mt-1 text-on-surface-variant">
                        {{ $registration->mother_occupation ?: 'Pekerjaan belum diisi' }}</p>
                    <p class="mt-1 text-on-surface">{{ $registration->mother_phone ?: 'Nomor HP belum diisi' }}</p>
                </div>
            </div>
        </article>
    </section>

    <section class="rounded-[28px] border border-outline-variant/20 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-on-surface">Status Berkas</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($documentStatuses as $document)
                @php
                    $path = $document['path'];
                    $label = $document['label'];
                    $documentUrl = $path ? Storage::disk('public')->url($path) : null;
                    $extension = $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : null;
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true);
                    $viewerType = $isImage ? 'image' : 'file';
                @endphp
                <div wire:key="doc-{{ $label }}" class="rounded-2xl border border-outline-variant/15 p-4">
                    <p class="text-sm font-semibold text-on-surface">{{ $label }}</p>
                    <p class="mt-2 text-xs font-medium {{ $path ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $path ? 'Sudah diunggah' : 'Belum diunggah' }}
                    </p>
                    <p class="mt-2 break-all text-xs text-on-surface-variant">{{ $path ?: '-' }}</p>

                    @if ($documentUrl)
                        <div class="mt-4 flex flex-col gap-2">
                            <button type="button"
                                @click="viewerOpen = true; viewerUrl = '{{ $documentUrl }}'; viewerLabel = '{{ $label }}'; viewerType = '{{ $viewerType }}'"
                                class="inline-flex items-center justify-center rounded-xl bg-primary px-3 py-2 text-xs font-semibold text-white">
                                Lihat Berkas
                            </button>
                            <a href="{{ $documentUrl }}" target="_blank" rel="noopener"
                                class="inline-flex items-center justify-center rounded-xl border border-outline-variant/30 px-3 py-2 text-xs font-semibold text-on-surface">
                                Buka Tab Baru
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <div x-cloak x-show="viewerOpen" x-transition.opacity.duration.200ms
        class="fixed inset-0 flex items-center justify-center bg-slate-900/70 px-4 py-6" style="z-index: 90;"
        @keydown.escape.window="viewerOpen = false">
        <div class="absolute inset-0" @click="viewerOpen = false"></div>
        <div
            class="relative z-10 flex max-h-[92vh] w-full max-w-5xl flex-col overflow-hidden rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-outline-variant/20 px-5 py-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Preview Berkas</p>
                    <h3 class="mt-1 text-lg font-bold text-on-surface" x-text="viewerLabel"></h3>
                </div>
                <button type="button" @click="viewerOpen = false"
                    class="rounded-full bg-surface-container px-3 py-2 text-sm font-semibold text-on-surface-variant">Tutup</button>
            </div>
            <div class="min-h-[60vh] flex-1 bg-slate-100 p-3">
                <template x-if="viewerType === 'image'">
                    <div class="flex h-full items-center justify-center overflow-auto rounded-2xl bg-white p-4">
                        <img :src="viewerUrl" :alt="viewerLabel"
                            class="max-h-[72vh] w-auto max-w-full rounded-2xl object-contain">
                    </div>
                </template>
                <template x-if="viewerType !== 'image'">
                    <iframe :src="viewerUrl" class="h-[72vh] w-full rounded-2xl bg-white"></iframe>
                </template>
            </div>
        </div>
    </div>
</div>
