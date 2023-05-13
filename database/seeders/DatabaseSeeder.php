<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Mohamed Tallal',
            'email' => 'mo@gmail.com',
            'password' => bcrypt(123456789)
        ]);

        $this->call(IngredientSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductIngredientSeeder::class);
    }
}
