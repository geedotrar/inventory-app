<?php

use App\Models\Product;

it('casts product stock and price to integers', function (): void {
    $product = new Product([
        'name' => 'Pulpen',
        'category_id' => 1,
        'stock' => '12',
        'price' => '250000',
    ]);

    expect($product->category_id)->toBe(1);

    expect($product->stock)->toBe(12);
    expect($product->price)->toBe(250000);
});