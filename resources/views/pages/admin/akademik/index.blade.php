<?php

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    public function getStatsProperty(): array
    {
        return [['label' => 'Total Siswa', 'value' => Student::query()->count(), 'route' => 'admin.akademik.siswa'], ['label' => 'Total Kelas', 'value' => SchoolClass::query()->count(), 'route' => 'admin.akademik.kelas'], ['label' => 'Total Guru', 'value' => Teacher::query()->count(), 'route' => 'admin.akademik.guru']];
    }

    public function getClassesProperty(): Collection
    {
        return SchoolClass::query()
            ->with(['homeroomTeacher:id,name'])
            ->withCount('students')
            ->ordered()
            ->get();
    }

    public function getRecentTeachersProperty(): Collection
    {
        return Teacher::query()->ordered()->limit(5)->get();
    }

    public function getRecentStudentsProperty(): Collection
    {
        return Student::query()->with('schoolClass:id,name')->ordered()->limit(5)->get();
    }
};
?>

<div class="space-y-6">
    <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Admin Akademik</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-800">Ringkasan Akademik</h1>
        <p class="mt-2 text-sm text-slate-600">Pantau jumlah siswa, kelas, guru, dan relasi wali kelas secara dinamis.
        </p>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Statistik Akademik</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($this->stats as $card)
                <article wire:key="akademik-stat-{{ $card['label'] }}" class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $card['value'] }}</p>
                    <a href="{{ route($card['route']) }}" wire:navigate
                        class="mt-3 inline-flex text-sm font-semibold text-sky-700 hover:underline">Buka detail</a>
                </article>
            @endforeach
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.1fr,0.9fr]">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Daftar Kelas</h2>
                    <p class="mt-1 text-sm text-slate-500">Ringkasan wali kelas dan jumlah siswa aktif per kelas.</p>
                </div>
                <a href="{{ route('admin.akademik.kelas') }}" wire:navigate
                    class="rounded-xl bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700">Kelola Kelas</a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[520px] text-left text-sm">
                    <thead class="border-b border-slate-200 text-slate-500">
                        <tr>
                            <th class="py-3 pr-3 font-medium">Kelas</th>
                            <th class="py-3 pr-3 font-medium">Wali Kelas</th>
                            <th class="py-3 pr-3 font-medium">Jumlah Siswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->classes as $class)
                            <tr wire:key="summary-class-{{ $class->id }}" class="border-b border-slate-100">
                                <td class="py-3 pr-3 font-semibold text-slate-800">Kelas {{ $class->name }}</td>
                                <td class="py-3 pr-3 text-slate-600">
                                    {{ $class->homeroomTeacher?->name ?? 'Belum ditentukan' }}</td>
                                <td class="py-3 pr-3 text-slate-600">{{ $class->students_count }} siswa</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-500">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <div class="space-y-6">
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-bold text-slate-800">Guru Terbaru</h2>
                    <a href="{{ route('admin.akademik.guru') }}" wire:navigate
                        class="rounded-xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Kelola Guru</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($this->recentTeachers as $teacher)
                        <article wire:key="summary-teacher-{{ $teacher->id }}"
                            class="rounded-2xl border border-slate-200 p-4">
                            <p class="font-semibold text-slate-800">{{ $teacher->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $teacher->employment_status }} ·
                                {{ $teacher->phone }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data guru.</p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-bold text-slate-800">Siswa Terbaru</h2>
                    <a href="{{ route('admin.akademik.siswa') }}" wire:navigate
                        class="rounded-xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Kelola
                        Siswa</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($this->recentStudents as $student)
                        <article wire:key="summary-student-{{ $student->id }}"
                            class="rounded-2xl border border-slate-200 p-4">
                            <p class="font-semibold text-slate-800">{{ $student->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $student->nis }} · Kelas
                                {{ $student->schoolClass?->name }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data siswa.</p>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
</div>
