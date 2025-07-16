<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $categoryIds = DB::table("category")->pluck("id")->toArray();

        if (empty($categoryIds)) {
            $this->command->warn("No categories in the table");
            return; // Salimos si no hay categorías
        }

        $products = [];

        for ($i = 1; $i <= 50; $i++) {
            $products[] = [
                "name" => $faker->word,
                "description" => $faker->sentence,
                "price" => $faker->randomFloat(2, 10, 500),
                "category_id" => $faker->randomElement($categoryIds),
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }

        DB::table('product')->insert($products);
    }
}

// Generando datos con faker
