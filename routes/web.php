<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Dashboard par défaut (ex : admin ou utilisateurs sans rôle spécifique)
Route::get('/dashboard', function () {
    if (auth()->user()?->role === 'vendeur') {
        return redirect()->route('vendeur.dashboard');
    }

    if (auth()->user()?->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if (auth()->user()?->role === 'client') {
        return redirect()->route('client.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes accessibles à tout utilisateur connecté
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
});

// Routes réservées aux vendeurs
Route::middleware(['auth', 'role:vendeur'])->prefix('vendeur')->group(function () {
    Route::get('/dashboard', function () {
        return view('vendeur.dashboard');
    })->name('vendeur.dashboard');

    Route::get('/produits', [ProductController::class, 'index'])->name('vendeur.produits.index');
    Route::get('/produits/ajouter', [ProductController::class, 'create'])->name('vendeur.produits.create');
    Route::post('/produits', [ProductController::class, 'store'])->name('vendeur.produits.store');
    Route::get('/produits/{product}/modifier', [ProductController::class, 'edit'])->name('vendeur.produits.edit');
    Route::patch('/produits/{product}', [ProductController::class, 'update'])->name('vendeur.produits.update');
    Route::delete('/produits/{product}', [ProductController::class, 'destroy'])->name('vendeur.produits.destroy');
});

// Routes réservées aux clients
Route::middleware(['auth', 'role:client'])->prefix('client')->group(function () {
    Route::get('/dashboard', function () {
        $products = Product::with('sizes')
            ->whereHas('sizes', fn ($query) => $query->where('stock', '>', 0))
            ->latest()
            ->take(6)
            ->get();

        return view('client.dashboard', compact('products'));
    })->name('client.dashboard');

    Route::get('/boutique', [ProductController::class, 'index'])->name('client.boutique');
    // Autres routes : panier, commandes...
});

// Routes réservées aux administrateurs
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    // Ajoute ici les routes d'administration
});

// Route de test middleware
Route::get('/test-role', function () {
    return 'Middleware role OK';
})->middleware('role:vendeur');

require __DIR__.'/auth.php';
