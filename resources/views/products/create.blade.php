<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajouter un produit') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-6 rounded-md bg-red-50 p-4 text-sm text-red-800">
                        <ul class="list-disc ps-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('vendeur.produits.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                        <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="description" name="description" rows="3" placeholder="Rouge, noir, blanc...">{{ old('description') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Laisser vide si une seule version du modele est disponible.</p>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Prix (FCFA)</label>
                        <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Image</label>
                        <input type="file" class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-200" id="image" name="image">
                    </div>

                    <div>
                        <div class="mb-3 flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Tailles et stock</h3>
                            <button type="button" class="rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50" id="add-size">
                                Ajouter une taille
                            </button>
                        </div>

                        <div id="sizes-container" class="space-y-3">
                            <div class="grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
                                <input type="text" name="sizes[0][size]" class="rounded-md border-gray-300 shadow-sm" placeholder="Taille (ex: 42)" required>
                                <input type="number" name="sizes[0][stock]" class="rounded-md border-gray-300 shadow-sm" placeholder="Stock" min="0" required>
                                <button type="button" class="remove-size rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">Retirer</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('vendeur.produits.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Annuler</a>
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let index = 1;

        document.getElementById('add-size').addEventListener('click', function () {
            const container = document.getElementById('sizes-container');
            const div = document.createElement('div');
            div.className = 'grid gap-3 sm:grid-cols-[1fr_1fr_auto]';
            div.innerHTML = `
                <input type="text" name="sizes[${index}][size]" class="rounded-md border-gray-300 shadow-sm" placeholder="Taille (ex: 42)" required>
                <input type="number" name="sizes[${index}][stock]" class="rounded-md border-gray-300 shadow-sm" placeholder="Stock" min="0" required>
                <button type="button" class="remove-size rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">Retirer</button>
            `;
            container.appendChild(div);
            index++;
        });

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-size') && document.querySelectorAll('#sizes-container > div').length > 1) {
                event.target.parentElement.remove();
            }
        });
    </script>
</x-app-layout>
