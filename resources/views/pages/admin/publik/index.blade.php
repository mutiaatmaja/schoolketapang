<?php

use App\Models\NewsArticle;
use App\Models\SchoolInformation;
use App\Models\VisionMission;
use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public function getSchoolInfoProperty(): array
    {
        return SchoolInformation::query()->ordered()->pluck('value', 'label')->all();
    }

    public function getSchoolMottoProperty(): ?string
    {
        return $this->schoolInfo['Motto Sekolah'] ?? null;
    }

    public function getSchoolDescriptionProperty(): ?string
    {
        return $this->schoolInfo['Informasi Sekolah'] ?? null;
    }

    public function getVisiProperty(): ?string
    {
        return VisionMission::query()->where('type', 'visi')->orderBy('sort_order')->value('content');
    }

    public function getMisiProperty(): array
    {
        return VisionMission::query()->where('type', 'misi')->orderBy('sort_order')->pluck('content')->all();
    }

    public function getSummaryProperty(): array
    {
        return [['label' => 'Jumlah Berita', 'count' => NewsArticle::query()->count(), 'route' => 'admin.publik.berita'], ['label' => 'Jumlah Prestasi', 'count' => 18, 'route' => 'admin.publik.prestasi'], ['label' => 'Jumlah Info', 'count' => SchoolInformation::query()->count(), 'route' => 'admin.publik.info-sekolah']];
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Publik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Kelola Konten Publik Sekolah</h1>
        <p class="mt-2 text-sm text-slate-600">Informasi sekolah, visi misi, dan ringkasan konten publik dalam satu
            halaman.</p>
    </header>
    <section id="ringkasan" class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
        <h2 class="text-lg font-bold text-slate-800">Ringkasan Konten Publik</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($this->summary as $card)
                <article wire:key="summary-{{ $card['label'] }}" class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $card['count'] }}</p>
                    <a href="{{ route($card['route']) }}" wire:navigate
                        class="mt-3 inline-flex text-sm font-semibold text-sky-700 hover:underline">
                        Buka tautan
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <section id="info-sekolah" class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-slate-800">Informasi Sekolah</h2>
            <a href="{{ route('admin.publik.info-sekolah') }}" wire:navigate
                class="text-sm font-semibold text-sky-700 hover:underline">Tautan Halaman</a>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
            <article class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wide text-sky-700">Motto Sekolah</p>
                <p class="mt-3 text-lg font-bold text-slate-800">
                    {{ $this->schoolMotto ?? 'Motto sekolah belum disimpan.' }}
                </p>
            </article>

            <article class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Informasi Sekolah</p>
                <p class="mt-3 text-sm leading-6 text-slate-700">
                    {{ $this->schoolDescription ?? 'Informasi sekolah belum disimpan.' }}
                </p>
            </article>
        </div>

        <dl class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
            @foreach ($this->schoolInfo as $label => $value)
                <div wire:key="info-{{ $label }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <dt class="text-xs uppercase tracking-wide text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </section>

    <section id="visi-misi" class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-slate-800">Visi & Misi</h2>
            <a href="{{ route('admin.publik.visi-misi') }}" wire:navigate
                class="text-sm font-semibold text-sky-700 hover:underline">Tautan Halaman</a>
        </div>

        <article class="mt-4 rounded-2xl bg-sky-50 p-4 border border-sky-100">
            <h3 class="text-sm font-bold text-sky-800">Visi</h3>
            <p class="mt-2 text-sm text-slate-700">{{ $this->visi ?? 'Belum ada visi yang disimpan.' }}</p>
        </article>

        <article class="mt-4 rounded-2xl bg-amber-50 p-4 border border-amber-100">
            <h3 class="text-sm font-bold text-amber-800">Misi</h3>
            <ul class="mt-2 space-y-2 text-sm text-slate-700 list-disc pl-5">
                @forelse ($this->misi as $item)
                    <li wire:key="misi-{{ md5($item) }}">{{ $item }}</li>
                @empty
                    <li>Belum ada misi yang disimpan.</li>
                @endforelse
            </ul>
        </article>
    </section>


</div>
