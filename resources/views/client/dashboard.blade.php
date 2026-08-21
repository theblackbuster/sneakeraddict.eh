{{-- resources/views/client/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Espace Client') }}
            </h2>

            <a href="{{ route('cart.index') }}"
               class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                Voir le panier
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Trouver vos sneakers
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Recherchez un modele, choisissez une taille disponible, puis ajoutez-le au panier.
                        </p>
                    </div>

                    <form action="{{ route('client.boutique') }}" method="GET" class="flex w-full flex-col gap-3 sm:flex-row lg:max-w-xl">
                        <input type="text"
                               name="search"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Rechercher une sneaker..."
                               value="{{ request('search') }}">
                        <button type="submit"
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Rechercher
                        </button>
                    </form>
                </div>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('client.boutique') }}"
                       class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                        Voir la boutique
                    </a>
                    <a href="{{ route('cart.index') }}"
                       class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        Ouvrir le panier
                    </a>
                </div>
            </div>

            <div>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Disponibles maintenant</h3>
                    <a href="{{ route('client.boutique') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Tout voir</a>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($products as $product)
                        <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-sneaker.png') }}"
                                 class="h-52 w-full object-cover"
                                 alt="{{ $product->name }}">
                            <div class="p-5">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $product->name }}</h4>
                                <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">{{ number_format($product->price, 0, ',', ' ') }} FCFA</p>

                                <form action="{{ route('cart.add') }}" method="POST" class="mt-4 space-y-3">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div>
                                        <label for="dashboard_size_{{ $product->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Taille</label>
                                        <select name="size_id" id="dashboard_size_{{ $product->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                            <option value="" disabled selected>Choisir une taille</option>
                                            @foreach($product->sizes as $size)
                                                @if($size->stock > 0)
                                                    <option value="{{ $size->id }}">{{ $size->size }} (Stock: {{ $size->stock }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="dashboard_quantity_{{ $product->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Quantite</label>
                                        <input type="number" name="quantity" id="dashboard_quantity_{{ $product->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="1" min="1" required>
                                    </div>

                                    <button type="submit" class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                        Ajouter au panier
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg bg-white p-6 text-gray-600 shadow-sm dark:bg-gray-800 dark:text-gray-300">
                            Aucun produit disponible pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
