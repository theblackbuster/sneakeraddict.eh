<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');
        $quantity = $request->input('quantity', 1);

        $product = Product::findOrFail($productId);
        $productSize = $product->sizes->where('id', $sizeId)->first();

        if (!$productSize || $productSize->stock < $quantity) {
            return redirect()->back()->with('error', 'Taille invalide ou stock insuffisant.');
        }

        $cart = session()->get('cart', []);
        $key = $product->id . '-' . $productSize->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'size' => $productSize->size,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produit ajouté au panier.');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Panier vidé.');
    }
}
