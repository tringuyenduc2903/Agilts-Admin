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
        Customer::factory(5)
            ->has(Address::factory(1)->customer())
            ->has(Address::factory(1, ['default' => true])->customer())
            ->create();

        Customer::factory(5)->emailUnverified()
            ->has(Address::factory(1)->customer())
            ->has(Address::factory(1, ['default' => true])->customer())
            ->create();

        Customer::factory(5)->phoneNumberUnverified()
            ->has(Address::factory(1)->customer())
            ->has(Address::factory(1, ['default' => true])->customer())
            ->create();
    }
}
