<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class InventoryManager extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';

    public bool $showFormModal = false;

    public ?int $editingProductId = null;

    public string $name = '';

    public string $categoryId = '';

    public string $stock = '';

    public string $price = '';

    public bool $showDeleteModal = false;

    public ?int $deletingProductId = null;

    public string $deletingProductName = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createProduct(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
        $this->dispatch('open-form-modal');
    }

    public function editProduct(int $productId): void
    {
        $product = Product::query()->findOrFail($productId);

        $this->editingProductId = $product->id;
        $this->name = $product->name;
        $this->categoryId = (string) $product->category_id;
        $this->stock = (string) $product->stock;
        $this->price = (string) $product->price;
        $this->showFormModal = true;
        $this->dispatch('open-form-modal');
    }

    public function save(): void
    {
        $isEditing = $this->editingProductId !== null;

        $validated = $this->validate(
            [
                'name' => ['required', 'string', 'min:3'],
                'categoryId' => ['required', 'integer', 'exists:categories,id'],
                'stock' => ['required', 'integer', 'min:1'],
                'price' => ['required', 'integer', 'min:0'],
            ],
            $this->messages(),
        );

        Product::query()->updateOrCreate(
            ['id' => $this->editingProductId],
            [
                'name' => $validated['name'],
                'category_id' => (int) $validated['categoryId'],
                'stock' => $validated['stock'],
                'price' => $validated['price'],
            ],
        );

        session()->flash('status', $isEditing
            ? 'Produk berhasil diperbarui.'
            : 'Produk berhasil ditambahkan.');

        $this->closeFormModal();
        $this->resetPage();
    }

    public function deleteProduct(int $productId): void
    {
        Product::query()->findOrFail($productId)->delete();

        session()->flash('status', 'Produk berhasil dihapus.');

        $this->cancelDeleteProduct();
        $this->resetPage();
    }

    public function confirmDeleteProduct(int $productId): void
    {
        $product = Product::query()->findOrFail($productId);

        $this->deletingProductId = $product->id;
        $this->deletingProductName = $product->name;
        $this->showDeleteModal = true;
        $this->dispatch('open-delete-modal');
    }

    public function cancelDeleteProduct(): void
    {
        $this->showDeleteModal = false;
        $this->deletingProductId = null;
        $this->deletingProductName = '';
        $this->dispatch('close-delete-modal');
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
        $this->dispatch('close-form-modal');
    }

    public function render()
    {
        $searchTerm = mb_strtolower($this->search);

        $products = Product::query()
            ->with('category')
            ->when(
                $searchTerm !== '',
                fn ($query) => $query->whereRaw('LOWER(name) LIKE ?', ['%'.$searchTerm.'%'])
            )
            ->orderBy('name')
            ->paginate(5);

        return view('livewire.inventory-manager', [
            'products' => $products,
            'totalProducts' => Product::query()->count(),
            'totalStock' => Product::query()->sum('stock'),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingProductId',
            'name',
            'categoryId',
            'stock',
            'price',
        ]);

        $this->resetValidation();
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.min' => 'Nama produk minimal 3 karakter.',
            'categoryId.required' => 'Kategori produk wajib dipilih.',
            'categoryId.integer' => 'Kategori produk tidak valid.',
            'categoryId.exists' => 'Kategori produk tidak ditemukan.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'stock.min' => 'Stok minimal 1.',
            'price.required' => 'Harga wajib diisi.',
            'price.integer' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
        ];
    }
}
