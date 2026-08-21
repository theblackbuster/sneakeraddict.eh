<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Votre panier est vide.');
        }

        return view('checkout.index', compact('cart'));
    }
}
