<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image', // pas de 'stock' ici, car stock par taille
    ];

    // Un produit a plusieurs tailles disponibles
    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
