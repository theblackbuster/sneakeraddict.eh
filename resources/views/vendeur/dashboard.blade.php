{{-- resources/views/vendeur/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de bord Vendeur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-gray-900 dark:text-gray-100 text-lg font-semibold">
                            Bienvenue sur votre espace vendeur !
                        </p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Ajoutez des sneakers, consultez vos produits et modifiez le stock par taille.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                    @if(Route::has('vendeur.produits.create'))
                        <a href="{{ route('vendeur.produits.create') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            Ajouter un produit
                        </a>
                    @endif

                    @if(Route::has('vendeur.produits.index'))
                        <a href="{{ route('vendeur.produits.index') }}"
                           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                            Voir et gerer mes produits
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
