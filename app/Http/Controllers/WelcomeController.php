<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use App\Models\SchoolAchievement;
use App\Models\SchoolInformation;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\VisionMission;
use Illuminate\Contracts\View\View;

class WelcomeController extends Controller
{
    public function __invoke(): View
    {
        $schoolInformation = SchoolInformation::query()->ordered()->get();
        $schoolInformationMap = $schoolInformation->pluck('value', 'label');

        $infoCards = collect([
            ['label' => 'NPSN', 'icon' => 'school'],
            ['label' => 'Nama Sekolah', 'icon' => 'domain'],
            ['label' => 'Alamat', 'icon' => 'location_on'],
            ['label' => 'Email', 'icon' => 'mail'],
            ['label' => 'No. Telepon', 'icon' => 'call'],
            ['label' => 'Website', 'icon' => 'language'],
            ['label' => 'Akreditasi', 'icon' => 'verified'],
        ])->map(function (array $item) use ($schoolInformationMap): ?array {
            $value = $schoolInformationMap->get($item['label']);

            if (! is_string($value) || $value === '') {
                return null;
            }

            return $item + ['value' => $value];
        })->filter()->values();

        $vision = VisionMission::query()
            ->where('type', 'visi')
            ->orderBy('sort_order')
            ->value('content');

        $missions = VisionMission::query()
            ->where('type', 'misi')
            ->orderBy('sort_order')
            ->pluck('content');

        $newsArticles = NewsArticle::query()
            ->published()
            ->orderByDesc('published_at')
            ->limit(5)
            ->get()
            ->map(fn (NewsArticle $article): array => [
                'title' => $article->title,
                'slug' => $article->slug,
                'date' => $article->published_at?->translatedFormat('d M Y') ?? '-',
                'category' => $article->category,
            ]);

        $achievementHighlights = SchoolAchievement::query()
            ->ordered()
            ->limit(4)
            ->get()
            ->map(fn (SchoolAchievement $achievement): array => [
                'title' => $achievement->title,
                'description' => $achievement->description,
                'level' => $achievement->level,
                'year' => $achievement->year,
            ]);

        $teacherCount = Teacher::query()->count('id');
        $studentCount = Student::query()->count('id');
        $achievementCount = SchoolAchievement::query()->count('id');

        return view('welcome', [
            'schoolName' => $schoolInformationMap->get('Nama Sekolah', 'Elementary School'),
            'schoolMotto' => $schoolInformationMap->get('Motto Sekolah', 'Cerdas, Berakhlak, dan Berprestasi Tanpa Batas'),
            'schoolDescription' => $schoolInformationMap->get('Informasi Sekolah', 'Mencetak generasi cerdas dan berakhlak mulia melalui pendidikan berkualitas dan lingkungan yang mendukung.'),
            'schoolInformationMap' => $schoolInformationMap,
            'infoCards' => $infoCards,
            'vision' => $vision,
            'missions' => $missions,
            'newsArticles' => $newsArticles,
            'contactPhone' => $schoolInformationMap->get('No. Telepon', '+62 812 3456 7890'),
            'contactEmail' => $schoolInformationMap->get('Email', 'info@school.edu'),
            'contactAddress' => $schoolInformationMap->get('Alamat', 'Alamat sekolah belum tersedia.'),
            'schoolNpsn' => $schoolInformationMap->get('NPSN', '-'),
            'teacherCount' => $teacherCount,
            'studentCount' => $studentCount,
            'achievementCount' => $achievementCount,
            'achievementHighlights' => $achievementHighlights,
            'remainingAchievementCount' => max($achievementCount - $achievementHighlights->count(), 0),
        ]);
    }
}
