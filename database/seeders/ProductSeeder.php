<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Pulpen', 'category' => 'ATK', 'stock' => 12, 'price' => 8500],
            ['name' => 'Kertas HVS', 'category' => 'ATK', 'stock' => 18, 'price' => 45000],
            ['name' => 'Stapler', 'category' => 'ATK', 'stock' => 7, 'price' => 32000],
            ['name' => 'Mouse Wireless', 'category' => 'Aksesoris Kantor', 'stock' => 20, 'price' => 125000],
            ['name' => 'Kursi Kantor', 'category' => 'Furniture Kantor', 'stock' => 9, 'price' => 875000],
        ];

        foreach ($products as $product) {
            $categoryId = Category::query()
                ->where('name', $product['category'])
                ->value('id');

            Product::query()->updateOrCreate(
                ['name' => $product['name']],
                [
                    'name' => $product['name'],
                    'category_id' => $categoryId,
                    'stock' => $product['stock'],
                    'price' => $product['price'],
                ],
            );
        }
    }
}