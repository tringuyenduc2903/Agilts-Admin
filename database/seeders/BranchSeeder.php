<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::factory(50)->create()->each(function (Branch $branch) {
            $branch->addresses()->saveMany(
                Address::factory(2)->branch()->make()
            );
        });
    }
}
