<?php

namespace Database\Seeders;

use App\Models\ProductsCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Art & Design',
                'icon' => 'category-icon-1.svg',
            ],
            [
                'name' => 'History',
                'icon' => 'category-icon-2.svg',
            ],
            [
                'name' => 'Science',
                'icon' => 'category-icon-3.svg',
            ],
            [
                'name' => 'Novels',
                'icon' => 'category-icon-4.svg',
            ],
            [
                'name' => 'Cooking',
                'icon' => 'category-icon-5.svg',
            ],
            [
                'name' => 'Self-help',
                'icon' => 'category-icon-6.svg',
            ],
            [
                'name' => 'Adventure',
                'icon' => 'category-icon-7.svg',
            ],
            [
                'name' => 'Fantasy',
                'icon' => 'category-icon-8.svg',
            ],
            [
                'name' => 'Romance',
                'icon' => 'category-icon-9.svg',
            ],
            [
                'name' => 'Business',
                'icon' => 'category-icon-10.svg',
            ],
        ];

        foreach ($categories as $sort => $category) {
            ProductsCategory::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => [
                        'original' => "products_categories/{$category['icon']}",
                    ],
                    'sort_order' => $sort + 1,
                    'status' => 1,
                ],
            );
        }
    }
}
