<?php

namespace App\Services;

use App\Models\NewsPost;
use Illuminate\Support\Collection;

class BlogService
{
    public function getLatestPosts(int $limit = 7): Collection
    {
        return NewsPost::query()
            ->where('status', 1)
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get()
            ->map(fn (NewsPost $post) => $this->formatPost($post));
    }

    protected function formatPost(NewsPost $post): array
    {
        return [
            'title' => $post->title,
            'slug' => $post->slug,
            'date' => $post->published_at?->toDateString(),
            'image' => $post->image['original'] ?? null,
            'category' => $post->category?->name,
        ];
    }
}
