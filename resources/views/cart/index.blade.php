<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Mon Panier') }}
    </h2>
</x-slot>

<div class="container">
    <h1>Mon Panier</h1>

    @if(session()->has('cart') && count(session('cart')) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Taille</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach(session('cart') as $item)
                    @php $itemTotal = $item['price'] * $item['quantity']; @endphp
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['size'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['price'], 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($itemTotal, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @php $total += $itemTotal; @endphp
                @endforeach
                <tr>
                    <td colspan="4"><strong>Total général</strong></td>
                    <td><strong>{{ number_format($total, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Actions supplémentaires -->
        <div class="mt-3">
            <form action="{{ route('cart.clear') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Vider le panier</button>
            </form>
            <a href="{{ route('checkout.index') }}" class="btn btn-success">Valider la commande</a>
        </div>

    @else
        <p>Votre panier est vide.</p>
    @endif
</div>
</x-app-layout>
