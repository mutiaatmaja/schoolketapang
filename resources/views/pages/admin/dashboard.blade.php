<?php

use App\Models\NewsArticle;
use App\Models\SchoolAchievement;
use App\Models\SchoolClass;
use App\Models\SchoolInformation;
use App\Models\SpmbRegistration;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    public function refreshStats(): void
    {
        $this->dispatch('toast', type: 'success', message: 'Statistik dashboard berhasil diperbarui.');
    }

    public function getSchoolInfoProperty(): array
    {
        return SchoolInformation::query()->ordered()->pluck('value', 'label')->all();
    }

    public function getSchoolNameProperty(): string
    {
        return $this->schoolInfo['Nama Sekolah'] ?? 'Dashboard Administrasi Sekolah';
    }

    public function getSchoolMottoProperty(): ?string
    {
        return $this->schoolInfo['Motto Sekolah'] ?? null;
    }

    public function getHeroStatsProperty(): array
    {
        $spmbCounts = SpmbRegistration::query()->selectRaw('COUNT(*) as total')->selectRaw("SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted")->first();

        return [
            [
                'label' => 'Menunggu Validasi',
                'value' => (int) ($spmbCounts?->submitted ?? 0),
                'route' => route('admin.ppdb.belum-validasi'),
            ],
            [
                'label' => 'Berita Dipublikasikan',
                'value' => NewsArticle::query()->published()->count(),
                'route' => route('admin.publik.berita'),
            ],
            [
                'label' => 'Draft Berita',
                'value' => NewsArticle::query()->where('status', 'draft')->count(),
                'route' => route('admin.publik.berita'),
            ],
            [
                'label' => 'Prestasi Sekolah',
                'value' => SchoolAchievement::query()->count(),
                'route' => route('admin.publik.prestasi'),
            ],
        ];
    }

    public function getSummaryCardsProperty(): array
    {
        $studentCounts = Student::query()->selectRaw('COUNT(*) as total')->selectRaw("SUM(CASE WHEN status = 'AKTIF' THEN 1 ELSE 0 END) as active")->first();

        $teacherCounts = Teacher::query()->selectRaw('COUNT(*) as total')->selectRaw("SUM(CASE WHEN employment_status = 'Tetap' THEN 1 ELSE 0 END) as permanent")->first();

        $spmbCounts = SpmbRegistration::query()->selectRaw('COUNT(*) as total')->selectRaw("SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted")->first();

        return [
            [
                'label' => 'Total Siswa',
                'value' => (int) ($studentCounts?->total ?? 0),
                'meta' => (int) ($studentCounts?->active ?? 0) . ' siswa aktif',
                'route' => route('admin.akademik.siswa'),
                'icon' => 'groups',
                'icon_class' => 'bg-primary/10 text-primary',
            ],
            [
                'label' => 'Total Guru',
                'value' => (int) ($teacherCounts?->total ?? 0),
                'meta' => (int) ($teacherCounts?->permanent ?? 0) . ' guru tetap',
                'route' => route('admin.akademik.guru'),
                'icon' => 'school',
                'icon_class' => 'bg-secondary/10 text-secondary',
            ],
            [
                'label' => 'Total Kelas',
                'value' => SchoolClass::query()->count(),
                'meta' => 'Struktur rombel aktif',
                'route' => route('admin.akademik.kelas'),
                'icon' => 'meeting_room',
                'icon_class' => 'bg-tertiary-fixed-dim/30 text-tertiary',
            ],
            [
                'label' => 'Pendaftar SPMB',
                'value' => (int) ($spmbCounts?->total ?? 0),
                'meta' => (int) ($spmbCounts?->submitted ?? 0) . ' belum divalidasi',
                'route' => route('admin.ppdb.pendaftar'),
                'icon' => 'app_registration',
                'icon_class' => 'bg-error-container/20 text-error',
            ],
        ];
    }

    public function getSpmbStatusesProperty(): array
    {
        $counts = SpmbRegistration::query()->selectRaw('COUNT(*) as total')->selectRaw("SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted")->selectRaw("SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as verified")->selectRaw("SUM(CASE WHEN status = 'lulus' THEN 1 ELSE 0 END) as lulus")->selectRaw("SUM(CASE WHEN status = 'cadangan' THEN 1 ELSE 0 END) as cadangan")->selectRaw("SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak")->first();

        return [
            [
                'label' => 'Belum Validasi',
                'value' => (int) ($counts?->submitted ?? 0),
                'description' => 'Pendaftar yang masih menunggu peninjauan berkas.',
                'route' => route('admin.ppdb.belum-validasi'),
                'card_class' => 'hover:border-amber-200 hover:bg-amber-50/60',
                'pill_class' => 'bg-amber-100 text-amber-800',
            ],
            [
                'label' => 'Terverifikasi',
                'value' => (int) ($counts?->verified ?? 0),
                'description' => 'Berkas sudah diverifikasi dan siap penetapan lanjutan.',
                'route' => route('admin.ppdb.pendaftar'),
                'card_class' => 'hover:border-sky-200 hover:bg-sky-50/60',
                'pill_class' => 'bg-sky-100 text-sky-800',
            ],
            [
                'label' => 'Lulus',
                'value' => (int) ($counts?->lulus ?? 0),
                'description' => 'Peserta yang dinyatakan lulus seleksi.',
                'route' => route('admin.ppdb.lulus'),
                'card_class' => 'hover:border-emerald-200 hover:bg-emerald-50/60',
                'pill_class' => 'bg-emerald-100 text-emerald-800',
            ],
            [
                'label' => 'Cadangan',
                'value' => (int) ($counts?->cadangan ?? 0),
                'description' => 'Peserta pada daftar cadangan aktif.',
                'route' => route('admin.ppdb.cadangan'),
                'card_class' => 'hover:border-violet-200 hover:bg-violet-50/60',
                'pill_class' => 'bg-violet-100 text-violet-800',
            ],
            [
                'label' => 'Ditolak',
                'value' => (int) ($counts?->ditolak ?? 0),
                'description' => 'Peserta yang tidak lolos pada tahap seleksi.',
                'route' => route('admin.ppdb.ditolak'),
                'card_class' => 'hover:border-rose-200 hover:bg-rose-50/60',
                'pill_class' => 'bg-rose-100 text-rose-800',
            ],
        ];
    }

    public function getLatestNewsProperty(): array
    {
        return NewsArticle::query()
            ->published()
            ->orderByDesc('published_at')
            ->limit(3)
            ->get()
            ->map(
                fn(NewsArticle $article): array => [
                    'title' => $article->title,
                    'category' => $article->category,
                    'excerpt' => str($article->excerpt ?: strip_tags($article->content))
                        ->limit(120)
                        ->toString(),
                    'date' => $article->published_at?->translatedFormat('d M Y') ?? '-',
                ],
            )
            ->all();
    }

    public function getLatestAchievementsProperty(): array
    {
        return SchoolAchievement::query()
            ->ordered()
            ->limit(3)
            ->get()
            ->map(
                fn(SchoolAchievement $achievement): array => [
                    'title' => $achievement->title,
                    'description' => str($achievement->description)->limit(100)->toString(),
                    'meta' => trim(
                        collect([$achievement->level, $achievement->year])
                            ->filter()
                            ->implode(' • '),
                    ),
                ],
            )
            ->all();
    }

    public function getSchoolHighlightsProperty(): array
    {
        return collect([['label' => 'Nama Sekolah', 'value' => $this->schoolInfo['Nama Sekolah'] ?? null], ['label' => 'NPSN', 'value' => $this->schoolInfo['NPSN'] ?? null], ['label' => 'Alamat', 'value' => $this->schoolInfo['Alamat'] ?? null], ['label' => 'No. Telepon', 'value' => $this->schoolInfo['No. Telepon'] ?? null]])
            ->filter(fn(array $item): bool => filled($item['value']))
            ->values()
            ->all();
    }

    public function getAdminProfileProperty(): array
    {
        $user = Auth::user();
        $roleLabel = $user?->roles()->pluck('display_name')->filter()->implode(', ');

        return [
            'name' => $user?->name ?? 'Admin Sekolah',
            'email' => $user?->email ?? '-',
            'role' => $roleLabel !== '' ? $roleLabel : 'Administrator',
        ];
    }
};
?>

<div class="space-y-8">
    <section class="relative overflow-hidden rounded-4xl bg-primary p-8 text-white shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary to-primary-container opacity-95"></div>
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <span
                    class="inline-flex rounded-full bg-white/20 px-4 py-1 text-[12px] font-semibold uppercase tracking-[0.24em]">
                    Dashboard Admin
                </span>
                <h1 class="mt-4 text-3xl font-bold tracking-tight">{{ $this->schoolName }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-white/90 md:text-base">
                    {{ $this->schoolMotto ?: 'Pantau data akademik, pendaftar SPMB, dan konten publik sekolah dari satu dashboard yang terhubung langsung ke data terbaru.' }}
                </p>
                <div class="mt-5 flex flex-wrap gap-3 text-sm text-white/90">
                    @foreach ($this->heroStats as $item)
                        <a href="{{ $item['route'] }}" wire:navigate
                            class="rounded-full border border-white/20 bg-white/10 px-4 py-2 transition hover:bg-white/20">
                            {{ $item['label'] }}: {{ number_format($item['value'], 0, ',', '.') }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.ppdb.pendaftar') }}" wire:navigate
                    class="rounded-2xl bg-secondary-container px-6 py-3 font-semibold text-on-secondary-container transition hover:scale-[1.02]">
                    Buka Data Pendaftar
                </a>
                <button wire:click="refreshStats" wire:loading.attr="disabled" wire:target="refreshStats"
                    class="rounded-2xl border border-white/30 bg-white/10 px-6 py-3 font-semibold text-white transition hover:bg-white/20 disabled:opacity-60">
                    <span wire:loading.remove wire:target="refreshStats">Perbarui Ringkasan</span>
                    <span wire:loading wire:target="refreshStats">Memperbarui...</span>
                </button>
            </div>
        </div>

        <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-10 left-1/4 h-32 w-32 rounded-full bg-secondary/30 blur-2xl"></div>
    </section>

    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($this->summaryCards as $card)
            <a href="{{ $card['route'] }}" wire:navigate
                class="rounded-3xl border border-outline-variant/10 bg-surface-container-lowest p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="{{ $card['icon_class'] }} flex h-12 w-12 items-center justify-center rounded-2xl">
                        <span class="material-symbols-outlined">{{ $card['icon'] }}</span>
                    </div>
                    <span
                        class="rounded-full bg-surface-container px-3 py-1 text-xs font-semibold text-on-surface-variant">
                        {{ $card['meta'] }}
                    </span>
                </div>

                <p class="mt-5 text-sm font-medium text-on-surface-variant">{{ $card['label'] }}</p>
                <p class="mt-2 text-4xl font-bold text-on-surface">{{ number_format($card['value'], 0, ',', '.') }}</p>
            </a>
        @endforeach
    </section>

    <div class="grid grid-cols-1 gap-8 xl:grid-cols-[1.6fr_1fr]">
        <div class="space-y-8">
            <section class="rounded-4xl border border-outline-variant/10 bg-surface-container-lowest p-8 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-bold text-primary">Status SPMB Saat Ini</h2>
                        <p class="mt-2 text-sm text-on-surface-variant">Ringkasan seluruh jalur status pendaftar
                            berdasarkan data terbaru.</p>
                    </div>
                    <a href="{{ route('admin.ppdb.index') }}" wire:navigate
                        class="text-sm font-semibold text-primary hover:underline">
                        Buka modul SPMB
                    </a>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($this->spmbStatuses as $status)
                        <a href="{{ $status['route'] }}" wire:navigate
                            class="{{ $status['card_class'] }} rounded-2xl border border-slate-200 bg-white p-5 transition">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-700">{{ $status['label'] }}</p>
                                <span class="{{ $status['pill_class'] }} rounded-full px-3 py-1 text-xs font-semibold">
                                    {{ number_format($status['value'], 0, ',', '.') }}
                                </span>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $status['description'] }}</p>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="rounded-4xl border border-outline-variant/10 bg-surface-container-lowest p-8 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-bold text-primary">Berita &amp; Pengumuman Terbaru</h2>
                        <p class="mt-2 text-sm text-on-surface-variant">Konten publik terakhir yang sudah tayang di
                            website sekolah.</p>
                    </div>
                    <a href="{{ route('admin.publik.berita') }}" wire:navigate
                        class="text-sm font-semibold text-primary hover:underline">
                        Kelola berita
                    </a>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
                    @forelse ($this->latestNews as $article)
                        <article class="rounded-3xl border border-slate-200 bg-white p-5">
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-800">
                                {{ $article['category'] ?: 'Berita' }}
                            </span>
                            <h3 class="mt-4 text-lg font-bold text-slate-800">{{ $article['title'] }}</h3>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $article['excerpt'] }}</p>
                            <p class="mt-4 text-xs font-medium uppercase tracking-wide text-slate-400">
                                {{ $article['date'] }}</p>
                        </article>
                    @empty
                        <div
                            class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-500 lg:col-span-3">
                            Belum ada berita yang dipublikasikan. Tambahkan berita dari modul publik agar dashboard
                            menampilkan pembaruan terbaru.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="space-y-8">
            <section class="rounded-4xl bg-primary p-8 text-white shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-white/70">Admin Aktif</p>
                <h2 class="mt-3 text-2xl font-bold">{{ $this->adminProfile['name'] }}</h2>
                <p class="mt-1 text-sm text-white/80">{{ $this->adminProfile['role'] }}</p>

                <dl class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-white/10 p-4">
                        <dt class="text-xs uppercase tracking-wide text-white/60">Email</dt>
                        <dd class="mt-1 text-sm font-semibold">{{ $this->adminProfile['email'] }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <dt class="text-xs uppercase tracking-wide text-white/60">Menunggu Validasi</dt>
                        <dd class="mt-1 text-sm font-semibold">
                            {{ number_format($this->heroStats[0]['value'], 0, ',', '.') }} pendaftar SPMB
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-4xl border border-outline-variant/10 bg-surface-container-lowest p-8 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-bold text-primary">Informasi Sekolah</h2>
                    <a href="{{ route('admin.publik.info-sekolah') }}" wire:navigate
                        class="text-sm font-semibold text-primary hover:underline">
                        Edit info
                    </a>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse ($this->schoolHighlights as $item)
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $item['label'] }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">{{ $item['value'] }}</p>
                        </div>
                    @empty
                        <div
                            class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                            Informasi sekolah belum diisi. Lengkapi data profil sekolah agar dashboard menampilkan
                            ringkasan yang lebih akurat.
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-4xl border border-outline-variant/10 bg-surface-container-lowest p-8 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-bold text-primary">Prestasi Terbaru</h2>
                    <a href="{{ route('admin.publik.prestasi') }}" wire:navigate
                        class="text-sm font-semibold text-primary hover:underline">
                        Kelola prestasi
                    </a>
                </div>

                <div class="mt-5 space-y-4">
                    @forelse ($this->latestAchievements as $achievement)
                        <article class="rounded-2xl border border-slate-200 bg-white p-4">
                            <h3 class="text-sm font-bold text-slate-800">{{ $achievement['title'] }}</h3>
                            @if ($achievement['meta'] !== '')
                                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-amber-700">
                                    {{ $achievement['meta'] }}</p>
                            @endif
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $achievement['description'] }}</p>
                        </article>
                    @empty
                        <div
                            class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                            Belum ada prestasi yang ditambahkan.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
