<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Validation de la commande') }}
    </h2>
</x-slot>

<div class="container">
    <h1>Validation de la commande</h1>

    <p>Merci de votre achat ! Voici un résumé de votre commande :</p>

    <ul class="list-group mb-3">
        @php $total = 0; @endphp
        @foreach($cart as $item)
            @php $itemTotal = $item['price'] * $item['quantity']; @endphp
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $item['name'] }} - Taille {{ $item['size'] }} x{{ $item['quantity'] }}
                <span>{{ number_format($itemTotal, 0, ',', ' ') }} FCFA</span>
            </li>
            @php $total += $itemTotal; @endphp
        @endforeach
        <li class="list-group-item d-flex justify-content-between">
            <strong>Total</strong>
            <strong>{{ number_format($total, 0, ',', ' ') }} FCFA</strong>
        </li>
    </ul>

    <a href="{{ route('products.index') }}" class="btn btn-primary">Retour à la boutique</a>
</div>
</x-app-layout>
