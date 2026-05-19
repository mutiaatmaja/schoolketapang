<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use App\Models\SchoolInformation;
use Illuminate\Http\Response;

class BeritaController extends Controller
{
    public function show(string $slug): Response
    {
        $article = NewsArticle::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
        $schoolInformation = SchoolInformation::query()->where('label', 'Nama Sekolah')->value('value');
        $summaryPoints = $this->extractSummaryPoints($article->content);

        return response()->view('berita.show', [
            'schoolName' => $schoolInformation,
            'article' => [
                'title' => $article->title,
                'date' => $article->published_at?->translatedFormat('d F Y') ?? '-',
                'category' => $article->category,
                'excerpt' => $article->excerpt,
                'content' => $article->content,
                'summaryPoints' => $summaryPoints,
            ],
            'slug' => $article->slug,
            'relatedArticles' => NewsArticle::query()
                ->published()
                ->whereKeyNot($article->id)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get()
                ->map(fn (NewsArticle $relatedArticle): array => [
                    'title' => $relatedArticle->title,
                    'date' => $relatedArticle->published_at?->translatedFormat('d F Y') ?? '-',
                    'category' => $relatedArticle->category,
                    'excerpt' => $relatedArticle->excerpt,
                    'slug' => $relatedArticle->slug,
                ])
                ->values()
                ->all(),
        ]);
    }

    private function extractSummaryPoints(string $html): array
    {
        $normalized = str_ireplace(
            ['</p>', '</div>', '</li>', '</h1>', '</h2>', '</h3>', '</h4>', '</h5>', '</h6>', '<br>', '<br/>', '<br />'],
            ["\n\n", "\n\n", "\n", "\n\n", "\n\n", "\n\n", "\n\n", "\n\n", "\n\n", "\n", "\n", "\n"],
            $html,
        );

        return collect(preg_split('/\R{2,}/', trim(strip_tags($normalized))) ?: [])
            ->map(fn (string $item): string => trim(preg_replace('/\s+/', ' ', $item) ?? ''))
            ->filter()
            ->take(5)
            ->values()
            ->all();
    }
}
