<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $app_name = config('app.name');
        $random_number = fake()->randomNumber();

        return [
            'name' => "$app_name $random_number",
            'phone_number' => vnfaker()->mobilephone(),
        ];
    }
}
