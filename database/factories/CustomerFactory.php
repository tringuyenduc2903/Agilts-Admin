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
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->freeEmail(),
            'email_verified_at' => now(),
            'phone_number' => $this->phoneNumber(),
            'phone_number_verified_at' => now(),
            'birthday' => fake()->dateTimeBetween(
                Carbon::now()->subYears(100),
                Carbon::now()->subYears(16)
            ),
            'gender' => fake()->randomElement(Gender::keys()),
            'password' => Str::password(20),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * @return string
     */
    protected function phoneNumber(): string
    {
        $phone_number = fake()->unique()->phoneNumber();
        $phone_number = str_replace(['-', ' ', '(', ')'], '', $phone_number);

        return substr($phone_number, 0, 11);
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
     * Indicate that the model's email address should be unverified.
     */
    public function phoneNumberUnverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'phone_number_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function trashed(): static
    {
        return $this->state(fn(array $attributes) => [
            'deleted_at' => now(),
        ]);
    }
}
