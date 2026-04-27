<div x-data="{ showFormModal: false, showDeleteModal: false }" x-on:open-form-modal.window="showFormModal = true"
    x-on:close-form-modal.window="showFormModal = false" x-on:open-delete-modal.window="showDeleteModal = true"
    x-on:close-delete-modal.window="showDeleteModal = false" class="relative min-h-screen overflow-hidden">
    <div class="absolute inset-0 opacity-40">
    </div>

    <div class="relative mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <span
                    class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-emerald-700">
                    Sistem Inventaris
                </span>

                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                    Manajemen Inventaris Produk
                </h1>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:min-w-md">
                <div
                    class="rounded-3xl border border-white/70 bg-white/85 p-4 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur">
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Total Produk</div>
                    <div class="mt-3 text-3xl font-semibold text-slate-950">{{ number_format($totalProducts) }}</div>
                </div>

                <div
                    class="rounded-3xl border border-white/70 bg-white/85 p-4 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur">
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Total Stok</div>
                    <div class="mt-3 text-3xl font-semibold text-slate-950">{{ number_format($totalStock) }}</div>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div
                class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        <div
            class="overflow-hidden rounded-4xl border border-white/60 bg-white/90 shadow-[0_24px_80px_rgba(15,23,42,0.12)] backdrop-blur">
            <div
                class="flex flex-col gap-4 border-b border-slate-200/80 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Daftar Produk</h2>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <label class="relative block w-full sm:w-80">
                        <span class="sr-only">Cari produk</span>
                        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Cari nama produk..."
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-4 text-sm text-slate-900 outline-none transition focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100">
                    </label>

                    <button type="button" wire:click="createProduct"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 cursor-pointer">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M10 4a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V5a1 1 0 011-1z" />
                        </svg>
                        Tambah Produk
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50/80">
                        <tr class="text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                            <th class="px-5 py-4 sm:px-6">Nama</th>
                            <th class="px-5 py-4 sm:px-6">Kategori</th>
                            <th class="px-5 py-4 sm:px-6">Stok</th>
                            <th class="px-5 py-4 sm:px-6">Harga</th>
                            <th class="px-5 py-4 text-right sm:px-6">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($products as $product)
                            <tr wire:key="product-{{ $product->id }}" class="transition hover:bg-slate-50/80">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="font-semibold text-slate-950">{{ $product->name }}</div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span
                                        class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                        {{ $product->category?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span
                                        class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
                                        {{ number_format($product->stock) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="font-semibold text-slate-950">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" wire:click="editProduct({{ $product->id }})"
                                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-800 cursor-pointer">
                                            Edit
                                        </button>

                                        <button type="button" wire:click="confirmDeleteProduct({{ $product->id }})"
                                            class="rounded-xl border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 cursor-pointer">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center sm:px-6">
                                    <div class="mx-auto max-w-sm">
                                        <div
                                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8" aria-hidden="true">
                                                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 text-base font-semibold text-slate-950">Belum ada data produk</h3>
                                        <p class="mt-2 text-sm text-slate-500">Tambahkan produk pertama atau ubah kata kunci
                                            pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200/80 px-5 py-4 sm:px-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <div x-cloak x-show="showFormModal" x-on:click.self="showFormModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
        <div
            class="w-full max-w-2xl rounded-4xl border border-white/70 bg-white p-6 shadow-[0_30px_100px_rgba(15,23,42,0.2)] sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-600">
                        {{ $editingProductId ? 'Edit Produk' : 'Produk Baru' }}
                    </p>
                </div>
            </div>

            <form wire:submit="save" class="mt-6 grid gap-5 sm:grid-cols-2">
                <label class="sm:col-span-2">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Nama Produk</span>
                    <input type="text" wire:model.defer="name" placeholder="Masukkan nama produk..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100">
                    @error('name')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </label>

                <label>
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Kategori</span>
                    <select wire:model.defer="categoryId"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100 cursor-pointer">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('categoryId')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </label>

                <label>
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Stok</span>
                    <input type="number" min="1" step="1" wire:model.defer="stock" placeholder="Masukkan jumlah stok..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100">
                    @error('stock')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </label>

                <label class="sm:col-span-2">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Harga</span>
                    <div class="relative">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">Rp</span>
                        <input type="number" min="0" step="1" wire:model.defer="price"
                            placeholder="Masukkan harga produk..."
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm outline-none transition focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100">
                    </div>
                    @error('price')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </label>

                <div class="sm:col-span-2 flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <button type="button" x-on:click="showFormModal = false"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>

                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60 cursor-pointer">
                        <span wire:loading.remove>{{ $editingProductId ? 'Perbarui' : 'Simpan' }}</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div x-cloak x-show="showDeleteModal" x-on:click.self="showDeleteModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
        <div
            class="w-full max-w-2xl rounded-4xl border border-white/70 bg-white p-6 shadow-[0_30px_100px_rgba(15,23,42,0.2)] sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rose-600">Hapus Produk</p>
                    <h3 class="mt-2 text-2xl font-semibold text-slate-950">Yakin ingin menghapus produk ini?</h3>
                </div>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" x-on:click="showDeleteModal = false"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>

                <button type="button" wire:click="deleteProduct({{ $deletingProductId }})"
                    class="inline-flex items-center justify-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-500 cursor-pointer">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>