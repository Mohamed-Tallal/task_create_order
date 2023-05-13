<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'name', 'unit', 'minimum_stock', 'stock','ingredient_alert'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ingredient) {
            if (is_null($ingredient->minimum_stock) || $ingredient->minimum_stock == 0) {
                $ingredient->minimum_stock = $ingredient->stock / 2;
            }
        });
    }
}
