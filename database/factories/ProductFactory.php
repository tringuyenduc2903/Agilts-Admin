<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\ProductVisibility;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(255),
            'description' => fake()->text(5000),
            'enabled' => fake()->boolean(),
            'visibility' => fake()->randomElement(ProductVisibility::keys()),
            'status' => fake()->randomElement(ProductStatus::keys()),
            'type' => fake()->randomElement(ProductType::keys()),
            'specifications' => json_encode([[
                'key' => fake()->text(50),
                'value' => fake()->text(255),
            ], [
                'key' => fake()->text(50),
                'value' => fake()->text(255),
            ], [
                'key' => fake()->text(50),
                'value' => fake()->text(255),
            ]]),
        ];
    }
}
