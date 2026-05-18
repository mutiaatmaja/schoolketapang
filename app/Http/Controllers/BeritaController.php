<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Http\Response;

class BeritaController extends Controller
{
    public function show(string $slug): Response
    {
        $article = NewsArticle::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->view('berita.show', [
            'article' => [
                'title' => $article->title,
                'date' => $article->published_at?->translatedFormat('d F Y') ?? '-',
                'category' => $article->category,
                'excerpt' => $article->excerpt,
                'content' => collect(preg_split('/\R{2,}/', trim($article->content)) ?: [])
                    ->filter(fn (string $paragraph): bool => $paragraph !== '')
                    ->values()
                    ->all(),
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
}
