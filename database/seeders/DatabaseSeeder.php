<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Category::factory(count: 5)->create();
        //Product::factory(count: 5)->create();

        Category::factory(count: 3)->create()->each(function ($category) {

            Product::factory(10)->create(["category_id" => $category->id]);
        });

        /*
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call([CategoryTableSeeder::class, ProductTableSeeder::class]);
        */
    }
}

// Combear seeders