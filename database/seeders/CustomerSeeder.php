<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory(5)->create()->each(function (Customer $customer) {
            $this->addresses($customer);
        });

        Customer::factory(5)->emailUnverified()->create()->each(function (Customer $customer) {
            $this->addresses($customer);
        });

        Customer::factory(5)->phoneNumberUnverified()->create()->each(function (Customer $customer) {
            $this->addresses($customer);
        });
    }

    /**
     * @param Customer $customer
     * @return void
     */
    protected function addresses(Customer $customer): void
    {
        $customer->addresses()->saveMany(
            Address::factory(2)->customer()->make()
        );
    }
}
