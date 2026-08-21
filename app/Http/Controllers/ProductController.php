<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::query()
            ->with('sizes')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|string',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        // Remplace une description vide ou blanche par "Modèle unique disponible"
        $description = trim($validated['description'] ?? '');
        if ($description === '') {
            $description = 'Modèle unique disponible';
        }

        // Upload de l'image si elle existe
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        // Création du produit
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $description,
            'price' => $validated['price'],
            'image' => $validated['image'] ?? null,
        ]);

        // Création des tailles associées
        foreach ($validated['sizes'] as $sizeData) {
            ProductSize::create([
                'product_id' => $product->id,
                'size' => $sizeData['size'],
                'stock' => $sizeData['stock'],
            ]);
        }

        return redirect()->route('vendeur.produits.index')->with('success', 'Produit ajouté avec succès !');
    }

    public function edit(Product $product)
    {
        $product->load('sizes');

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sizes' => 'required|array|min:1',
            'sizes.*.id' => 'nullable|integer|exists:product_sizes,id',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        $description = trim($validated['description'] ?? '');

        $productData = [
            'name' => $validated['name'],
            'description' => $description === '' ? 'Modele unique disponible' : $description,
            'price' => $validated['price'],
        ];

        if ($request->hasFile('image')) {
            $productData['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($productData);

        foreach ($validated['sizes'] as $sizeData) {
            if (!empty($sizeData['id'])) {
                $product->sizes()
                    ->whereKey($sizeData['id'])
                    ->update([
                        'size' => $sizeData['size'],
                        'stock' => $sizeData['stock'],
                    ]);

                continue;
            }

            $product->sizes()->create([
                'size' => $sizeData['size'],
                'stock' => $sizeData['stock'],
            ]);
        }

        return redirect()->route('vendeur.produits.index')->with('success', 'Produit mis a jour avec succes !');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('vendeur.produits.index')->with('success', 'Produit supprime avec succes !');
    }
}
