<?php

namespace Database\Factories;

use App\Enums\ProductDetailStatus;
use App\Models\Branch;
use App\Models\ProductDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductDetail>
 */
class ProductDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chassis_number' => fake()->unique()->randomNumber(9),
            'engine_number' => fake()->unique()->randomNumber(9),
            'status' => fake()->randomElement(ProductDetailStatus::keys()),
            'branch_id' => Branch::inRandomOrder()->first()->id,
        ];
    }
}
