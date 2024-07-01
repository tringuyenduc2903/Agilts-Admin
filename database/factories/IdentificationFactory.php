<?php

namespace Database\Factories;

use App\Models\Identification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Identification>
 */
class IdentificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @param bool $default
     * @return array<string, mixed>
     */
    public function definition(bool $default = false): array
    {
        return [
            'type' => fake()->randomElement(\App\Enums\Identification::keys()),
            'number' => fake()->randomNumber(9),
            'issued_name' => vnfaker()->company(),
            'issuance_date' => fake()->dateTimeBetween(),
            'expiry_date' => fake()->dateTimeBetween('now', '+10 year'),
            'default' => $default,
        ];
    }
}
