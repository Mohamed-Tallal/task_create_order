<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = Ingredient::pluck('id')->toArray();
        Product::all()->each(function($product) use ($ingredients) {
            $product->ingredients()->attach(Arr::random($ingredients, rand(1, 3)) , ['quantity' => rand(3, 10)]);
        });
    }
}
