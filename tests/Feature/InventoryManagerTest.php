<?php

use App\Livewire\InventoryManager;
use App\Models\Category;
use App\Models\Product;
use Livewire\Livewire;

it('displays the inventory page', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee('Daftar Produk');
});

it('can create a product', function (): void {
    $category = Category::query()->create([
        'name' => 'ATK',
    ]);

    Livewire::test(InventoryManager::class)
        ->call('createProduct')
        ->set('name', 'Map')
        ->set('categoryId', $category->id)
        ->set('stock', 25)
        ->set('price', 22000)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('products', [
        'name' => 'Map',
        'category_id' => $category->id,
        'stock' => 25,
        'price' => 22000,
    ]);
});

it('filters products by name', function (): void {
    $minuman = Category::query()->create([
        'name' => 'Aksesoris Kantor',
    ]);

    Product::query()->create([
        'name' => 'Mouse Wireless',
        'category_id' => $minuman->id,
        'stock' => 10,
        'price' => 125000,
    ]);

    Product::query()->create([
        'name' => 'Kursi Kantor',
        'category_id' => $minuman->id,
        'stock' => 12,
        'price' => 875000,
    ]);

    Livewire::test(InventoryManager::class)
        ->set('search', 'mOuSe')
        ->assertSee('Mouse Wireless')
        ->assertDontSee('Kursi Kantor');
});

it('validates the product form', function (): void {
    Livewire::test(InventoryManager::class)
        ->call('createProduct')
        ->set('name', 'AB')
        ->set('categoryId', '')
        ->set('stock', 0)
        ->set('price', '')
        ->call('save')
        ->assertHasErrors([
            'name',
            'categoryId',
            'stock',
            'price',
        ]);
});

it('can delete a product', function (): void {
    $category = Category::query()->create([
        'name' => 'Elektronik Kantor',
    ]);

    $product = Product::query()->create([
        'name' => 'Printer',
        'category_id' => $category->id,
        'stock' => 9,
        'price' => 350000,
    ]);

    Livewire::test(InventoryManager::class)
        ->call('deleteProduct', $product->id);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});

it('cancels the delete confirmation modal without removing the product', function (): void {
    $category = Category::query()->create([
        'name' => 'ATK',
    ]);

    $product = Product::query()->create([
        'name' => 'Pulpen',
        'category_id' => $category->id,
        'stock' => 9,
        'price' => 8500,
    ]);

    Livewire::test(InventoryManager::class)
        ->call('confirmDeleteProduct', $product->id)
        ->assertSet('showDeleteModal', true)
        ->call('cancelDeleteProduct')
        ->assertSet('showDeleteModal', false);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
    ]);
});