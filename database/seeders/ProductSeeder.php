<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductOption;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(50)
            ->has(
                ProductOption::factory(5)->has(
                    ProductDetail::factory(100),
                    'details'
                ),
                'options'
            )
            ->create()
            ->each(function (Product $product) {
                return $product->categories()->saveMany(
                    Category::inRandomOrder()->limit(5)->get()
                );
            });
    }
}
