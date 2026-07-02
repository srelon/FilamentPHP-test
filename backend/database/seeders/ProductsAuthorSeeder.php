<?php

namespace Database\Seeders;

use App\Models\ProductsAuthor;
use Illuminate\Database\Seeder;

class ProductsAuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            [
                'slug' => 'oliver-hartman',
                'name' => 'Oliver Hartman',
                'avatar_color' => '#ff6310',
                'content' => 'Oliver Hartman is a bestselling author known for his compelling narratives and deep character studies across multiple genres.',
            ],
            [
                'slug' => 'clara-whitfield',
                'name' => 'Clara Whitfield',
                'avatar_color' => '#6b4fff',
                'content' => 'Clara Whitfield writes with elegance and precision, crafting stories that linger long after the last page is turned.',
            ],
            [
                'slug' => 'henry-caldwell',
                'name' => 'Henry Caldwell',
                'avatar_color' => '#18b96e',
                'content' => 'Henry Caldwell is celebrated for his rich historical fiction and immersive world-building that transports readers to another era.',
            ],
            [
                'slug' => 'amelia-brooks',
                'name' => 'Amelia Brooks',
                'avatar_color' => '#e84393',
                'content' => 'Amelia Brooks combines vivid imagination with emotional depth to create unforgettable journeys through her books.',
            ],
            [
                'slug' => 'nathaniel-parker',
                'name' => 'Nathaniel Parker',
                'avatar_color' => '#f5a623',
                'content' => 'Nathaniel Parker is a prolific author whose works span science, fantasy, and adventure with a unique poetic voice.',
            ],
            [
                'slug' => 'eleanor-finch',
                'name' => 'Eleanor Finch',
                'avatar_color' => '#2196f3',
                'content' => 'Eleanor Finch writes thought-provoking literary fiction that challenges conventions and celebrates the human spirit.',
            ]
        ];

        foreach ($authors as $author) {
            ProductsAuthor::firstOrCreate(['slug' => $author['slug']], $author);
        }
    }
}
