<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Peralatan Kantor',
            'ATK',
            'Elektronik Kantor',
            'Furniture Kantor',
            'Aksesoris Kantor',
        ];

        foreach ($categories as $categoryName) {
            Category::query()->updateOrCreate(
                ['name' => $categoryName],
                ['name' => $categoryName],
            );
        }
    }
}