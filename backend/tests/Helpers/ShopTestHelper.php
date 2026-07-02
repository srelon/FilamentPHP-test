<?php

namespace Tests\Helpers;

use App\Models\ContactInfo;
use App\Models\Menu;
use App\Models\NewsCategory;
use App\Models\NewsPost;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductsAuthor;
use App\Models\ProductsCategory;
use App\Models\ProductStock;

trait ShopTestHelper
{
    protected function createCategory(array $overrides = []): ProductsCategory
    {
        return ProductsCategory::create(array_merge([
            'name' => 'Test Category ' . uniqid(),
            'slug' => 'test-category-' . uniqid(),
            'icon' => [
                'original' => 'products_categories/test-icon.svg',
            ],
            'image' => [
                'original' => 'products_categories/test-promo.webp',
            ],
            'status' => 1,
            'sort_order' => 1,
        ], $overrides));
    }

    protected function createAuthor(array $overrides = []): ProductsAuthor
    {
        return ProductsAuthor::create(array_merge([
            'name' => 'Test Author ' . uniqid(),
            'slug' => 'test-author-' . uniqid(),
        ], $overrides));
    }

    protected function createProduct(?ProductsCategory $category = null, array $overrides = []): Product
    {
        return Product::create(array_merge([
            'category_id' => ($category ?? $this->createCategory())->id,
            'author_id' => $this->createAuthor()->id,
            'title' => 'Test Book ' . uniqid(),
            'slug' => 'test-book-' . uniqid(),
            'status' => 1,
        ], $overrides));
    }

    protected function createProductImage(Product $product, array $overrides = []): ProductImage
    {
        return ProductImage::create(array_merge([
            'product_id' => $product->id,
            'image' => [
                'original' => 'products/test-cover.webp',
            ],
            'sort_order' => 1,
        ], $overrides));
    }

    protected function createProductStock(Product $product, array $overrides = []): ProductStock
    {
        return ProductStock::create(array_merge([
            'product_id' => $product->id,
            'quantity' => 10,
            'price' => '19.99',
            'status' => 1,
            'sort_order' => 1,
        ], $overrides));
    }

    protected function createNewsCategory(array $overrides = []): NewsCategory
    {
        return NewsCategory::create(array_merge([
            'name' => 'Test News Category ' . uniqid(),
            'slug' => 'test-news-category-' . uniqid(),
            'status' => 1,
        ], $overrides));
    }

    protected function createNewsPost(array $overrides = []): NewsPost
    {
        return NewsPost::create(array_merge([
            'title' => 'Test Post ' . uniqid(),
            'slug' => 'test-post-' . uniqid(),
            'category_id' => $this->createNewsCategory()->id,
            'image' => [
                'original' => 'news/test-post.webp',
            ],
            'status' => 1,
            'published_at' => now(),
        ], $overrides));
    }

    protected function createMenuItem(array $overrides = []): Menu
    {
        return Menu::create(array_merge([
            'name' => 'Test Menu ' . uniqid(),
            'route' => 'home',
            'type' => 'route',
            'location' => 'header',
            'sort_order' => 1,
        ], $overrides));
    }

    protected function createContact(array $overrides = []): ContactInfo
    {
        return ContactInfo::create(array_merge([
            'key' => 'test-' . uniqid(),
            'name' => 'Test Contact',
            'content' => 'test value',
            'status' => 1,
            'sort_order' => 1,
        ], $overrides));
    }
}
