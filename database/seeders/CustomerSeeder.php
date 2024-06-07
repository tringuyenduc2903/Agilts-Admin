<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory(10)->create();
        Customer::factory(10)->emailUnverified()->create();
        Customer::factory(10)->phoneNumberUnverified()->create();
        Customer::factory(10)->trashed()->create();
    }
}
