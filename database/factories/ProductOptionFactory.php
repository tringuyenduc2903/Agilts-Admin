<?php

namespace Database\Factories;

use App\Models\ProductOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductOption>
 */
class ProductOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first = strtoupper(fake()->unique()->text(10));
        $handle = str_replace(['.', ','], '', $first);
        $handle = str_replace(' ', '_', $handle);

        $color = fake()->colorName();
        $model_name = fake()->year();

        return [
            'sku' =>
                sprintf(
                    '%s_%s_%s',
                    $handle,
                    mb_strtoupper($color),
                    $model_name
                ),
            'price' => fake()->randomFloat(2, 10000000, 1000000000),
            'color' => $color,
            'model_name' => $model_name,
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
