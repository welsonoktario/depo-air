<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Depo::factory(5)->create();
        \App\Models\Customer::factory(10)->create();
        \App\Models\Kurir::factory(5)->create();
        \App\Models\Barang::factory(10)->create();
    }
}
