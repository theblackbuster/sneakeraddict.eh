<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ request()->routeIs('vendeur.*') ? __('Mes produits') : __('Nos Sneakers') }}
            </h2>

            @if(request()->routeIs('vendeur.*'))
                <a href="{{ route('vendeur.produits.create') }}"
                   class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Ajouter un produit
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-md bg-red-50 p-4 text-sm font-medium text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ request()->routeIs('vendeur.*') ? route('vendeur.produits.index') : (request()->routeIs('client.*') ? route('client.boutique') : route('products.index')) }}" method="GET" class="mb-6 flex flex-col gap-3 sm:flex-row">
                <input type="text"
                       name="search"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Rechercher une sneaker..."
                       value="{{ request('search') }}">
                <button class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50" type="submit">
                    Rechercher
                </button>
            </form>

            @if(request()->routeIs('vendeur.*'))
                <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Produit</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Prix</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Stock par taille</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($products as $product)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-sneaker.png') }}"
                                                 class="h-14 w-14 rounded-md object-cover"
                                                 alt="{{ $product->name }}">
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $product->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $product->description ?: 'Modele unique disponible' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-200">
                                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-200">
                                        @forelse($product->sizes as $size)
                                            <span class="mb-1 mr-1 inline-flex rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                                {{ $size->size }}: {{ $size->stock }}
                                            </span>
                                        @empty
                                            <span class="text-gray-500">Aucune taille</span>
                                        @endforelse
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('vendeur.produits.edit', $product) }}"
                                               class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                                Modifier le stock
                                            </a>
                                            <form action="{{ route('vendeur.produits.destroy', $product) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        Aucun produit trouve.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($products as $product)
                        <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-sneaker.png') }}"
                                 class="h-56 w-full object-cover"
                                 alt="{{ $product->name }}">
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
                                <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">{{ number_format($product->price, 0, ',', ' ') }} FCFA</p>
                                <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $product->description && trim($product->description) != '' ? $product->description : 'Modele unique disponible' }}
                                </p>

                                @if ($product->sizes->where('stock', '>', 0)->count())
                                    <form action="{{ route('cart.add') }}" method="POST" class="mt-4 space-y-3">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div>
                                            <label for="size_{{ $product->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Taille</label>
                                            <select name="size_id" id="size_{{ $product->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                                <option value="" disabled selected>Choisir une taille</option>
                                                @foreach($product->sizes as $size)
                                                    @if($size->stock > 0)
                                                        <option value="{{ $size->id }}">{{ $size->size }} (Stock: {{ $size->stock }})</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="quantity_{{ $product->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Quantite</label>
                                            <input type="number" name="quantity" id="quantity_{{ $product->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="1" min="1" required>
                                        </div>

                                        <button type="submit" class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                            Ajouter au panier
                                        </button>
                                    </form>
                                @else
                                    <p class="mt-4 text-sm text-gray-500">Aucune taille disponible</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Aucun produit trouve.</p>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
