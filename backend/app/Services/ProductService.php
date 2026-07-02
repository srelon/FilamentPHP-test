<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductsAuthor;
use App\Models\ProductsCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductService
{
    public function getCategories(): Collection
    {
        return ProductsCategory::query()
            ->where('status', 1)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (ProductsCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'icon' => $category->icon['original'] ?? null,
                'image' => $category->image['original'] ?? null,
                'count' => $category->products_count,
            ]);
    }

    public function getBestsellers(int $limit = 8): Collection
    {
        return $this->baseQuery()
            ->orderByDesc('bestseller')
            ->orderByDesc('rating_avg')
            ->limit($limit)
            ->get()
            ->map(fn (Product $product) => $this->formatProduct($product));
    }

    public function getBestRated(int $limit = 9): Collection
    {
        return $this->baseQuery()
            ->orderByDesc('rating_avg')
            ->limit($limit)
            ->get()
            ->map(fn (Product $product) => $this->formatProduct($product));
    }

    public function getBestAuthor(): ?array
    {
        $ranked = Product::query()
            ->where('status', 1)
            ->selectRaw('author_id, SUM(bestseller) as bestseller_sum, AVG(rating_avg) as avg_rating')
            ->groupBy('author_id')
            ->orderByDesc('bestseller_sum')
            ->orderByDesc('avg_rating')
            ->first();

        $author = $ranked ? ProductsAuthor::find($ranked->author_id) : null;

        if (!$author) {
            return null;
        }

        return [
            'name' => $author->name,
            'slug' => $author->slug,
            'content' => $author->content,
            'photo' => $author->photo['original'] ?? null,
        ];
    }

    protected function baseQuery(): Builder
    {
        return Product::query()
            ->where('status', 1)
            ->with(['author', 'category', 'activeStock', 'primaryImage']);
    }

    protected function formatProduct(Product $product): array
    {
        return [
            'slug' => $product->slug,
            'title' => $product->title,
            'author' => $product->author?->name,
            'category' => $product->category?->name,
            'price' => $product->activeStock?->price,
            'rating' => (float) $product->rating_avg,
            'image' => $product->primaryImage?->image['original'] ?? null,
        ];
    }
}
