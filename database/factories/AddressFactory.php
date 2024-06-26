<?php

namespace Database\Factories;

use App\Enums\Address\Branch;
use App\Enums\Address\Customer;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Client\ConnectionException;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws ConnectionException
     */
    public function definition(): array
    {
        $province = $this->getProvince();
        $district = $this->getDistrict($province['id']);
        $ward_name = $this->getWardName($district['id']);

        return [
            'country' => 'Việt Nam',
            'province' => $province['full_name'],
            'district' => $district['full_name'],
            'ward' => $ward_name,
            'address_detail' => fake()->streetAddress,
            'default' => fake()->boolean,
        ];
    }

    /**
     * @return array
     * @throws ConnectionException
     */
    protected function getProvince(): array
    {
        $provinces = app(\App\Cache\Address::class)->provinces();

        return fake()->randomElement($provinces);
    }

    /**
     * @param string $province_id
     * @return array
     * @throws ConnectionException
     */
    protected function getDistrict(string $province_id): array
    {
        $districts = app(\App\Cache\Address::class)->districts($province_id);

        return fake()->randomElement($districts);
    }

    /**
     * @param string $district_id
     * @return ?string
     * @throws ConnectionException
     */
    protected function getWardName(string $district_id): ?string
    {
        $wards = app(\App\Cache\Address::class)->wards($district_id);

        return $wards
            ? fake()->randomElement($wards)['full_name']
            : null;
    }

    /**
     * Indicate that the model's customer.
     */
    public function customer(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => fake()->randomElement(Customer::keys()),
        ]);
    }

    /**
     * Indicate that the model's branch.
     */
    public function branch(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => fake()->randomElement(Branch::keys()),
        ]);
    }
}
