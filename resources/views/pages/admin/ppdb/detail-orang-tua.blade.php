<?php

use App\Models\User;
use Livewire\Component;

new class extends Component {
    public User $parent;

    public function mount(User $user): void
    {
        abort_unless($user->hasRole('orang_tua'), 404);

        $this->parent = $user->load([
            'spmbRegistrations' => function ($query): void {
                $query->latest('submitted_at')->latest('id');
            },
        ]);
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin SPMB</p>
                <h1 class="mt-2 text-2xl font-bold text-slate-800">Detail Akun Orang Tua</h1>
                <p class="mt-2 text-sm text-slate-600">Lihat akun parent beserta nama anak yang sudah dimasukkan ke SPMB.</p>
            </div>
            <a href="{{ route('admin.ppdb.orang-tua') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                Kembali ke daftar akun
            </a>
        </div>
    </header>

    <section class="grid gap-6 xl:grid-cols-[340px,minmax(0,1fr)]">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800">Profil Orang Tua</h2>
            <dl class="mt-4 space-y-4 text-sm text-slate-600">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Nama</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $parent->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Email</dt>
                    <dd class="mt-1">{{ $parent->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Jumlah Anak Terdaftar</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $parent->spmbRegistrations->count() }}</dd>
                </div>
            </dl>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Anak Yang Dimasukkan</h2>
                    <p class="mt-1 text-sm text-slate-500">Setiap pendaftaran menampilkan nama anak, nomor pendaftaran, dan status terakhir.</p>
                </div>
            </div>

            <div class="mt-4 space-y-4">
                @forelse ($parent->spmbRegistrations as $registration)
                    <article wire:key="parent-child-{{ $registration->id }}" class="rounded-2xl border border-slate-200 p-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-lg font-bold text-slate-800">{{ $registration->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $registration->registration_number }}</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                {{ $registration->status }}
                            </span>
                        </div>

                        <div class="mt-4 grid gap-4 text-sm text-slate-600 md:grid-cols-2 xl:grid-cols-3">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">NIK</p>
                                <p class="mt-1">{{ $registration->nik }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Tanggal Lahir</p>
                                <p class="mt-1">{{ $registration->birth_date?->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Dikirim</p>
                                <p class="mt-1">{{ $registration->submitted_at?->format('d M Y H:i') ?? '-' }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">
                        Belum ada anak yang didaftarkan oleh akun ini.
                    </div>
                @endforelse
            </div>
        </article>
    </section>
</div>