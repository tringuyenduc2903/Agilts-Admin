<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => vnfaker()->fullname(),
            'email' => vnfaker()->email(),
            'email_verified_at' => now(),
            'phone_number' => vnfaker()->mobilephone(),
            'phone_number_verified_at' => now(),
            'birthday' => fake()->dateTimeBetween(
                Carbon::now()->subYears(100),
                Carbon::now()->subYears(16)
            ),
            'gender' => fake()->randomElement(Gender::keys()),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function emailUnverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model's phone number address should be unverified.
     */
    public function phoneNumberUnverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'phone_number_verified_at' => null,
        ]);
    }
}
