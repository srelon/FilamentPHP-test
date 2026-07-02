<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductsAuthor;
use App\Models\ProductsCategory;
use App\Models\ProductStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private const PLACEHOLDER_QUANTITY = 50;

    public function run(): void
    {
        $products = [
            [
                'title' => 'Astral Journey',
                'category' => 'Fantasy',
                'author' => 'Nathaniel Parker',
                'price' => 28.00,
                'rating' => 5,
                'bestseller' => true,
                'images' => ['book-image-24.webp'],
            ],
            [
                'title' => 'Best Italian Cuisines',
                'category' => 'Cooking',
                'author' => 'Nathaniel Parker',
                'price' => 25.00,
                'rating' => 4,
                'bestseller' => true,
                'images' => ['book-image-7.webp'],
            ],
            [
                'title' => 'Economic Opportunity',
                'category' => 'Business',
                'author' => 'Clara Whitfield',
                'price' => 22.00,
                'rating' => 5,
                'bestseller' => false,
                'images' => ['book-image-32.webp'],
            ],
            [
                'title' => 'Journey Through Time',
                'category' => 'History',
                'author' => 'Amelia Brooks',
                'price' => 19.00,
                'rating' => 4,
                'bestseller' => false,
                'images' => ['book-image-25.webp'],
            ],
            [
                'title' => 'Simple Aesthetics',
                'category' => 'Art & Design',
                'author' => 'Amelia Brooks',
                'price' => 32.00,
                'rating' => 5,
                'bestseller' => false,
                'images' => ['book-image-26.webp'],
            ],
            [
                'title' => 'The Silent Forest',
                'category' => 'Novels',
                'author' => 'Eleanor Finch',
                'price' => 21.00,
                'rating' => 3,
                'bestseller' => false,
                'images' => ['book-image-33.webp'],
            ],
            [
                'title' => 'Mind & Wellness',
                'category' => 'Self-help',
                'author' => 'Oliver Hartman',
                'price' => 15.00,
                'rating' => 4,
                'bestseller' => false,
                'images' => ['book-image-18.webp'],
            ],
        ];

        foreach ($products as $data) {
            $category = ProductsCategory::where('name', $data['category'])->firstOrFail();
            $author = ProductsAuthor::where('name', $data['author'])->firstOrFail();

            $product = Product::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'category_id' => $category->id,
                    'author_id' => $author->id,
                    'title' => $data['title'],
                    'short_description' => $data['short_description'] ?? null,
                    'description' => $data['description'] ?? null,
                    'rating_avg' => $data['rating'],
                    'bestseller' => $data['bestseller'] ? 1 : 0,
                    'status' => 1,
                    'published_at' => now(),
                ],
            );

            foreach ($data['images'] as $sort => $image) {
                ProductImage::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'sort_order' => $sort + 1,
                    ],
                    ['image' => ['original' => "products/{$image}"]],
                );
            }

            ProductStock::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'status' => 1,
                ],
                [
                    'quantity' => self::PLACEHOLDER_QUANTITY,
                    'price' => $data['price'],
                    'sort' => 1,
                ],
            );
        }
    }
}
