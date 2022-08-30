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
        \App\Models\User::query()->create([
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
            'telepon' => '081234567890',
            'role' => 'Super Admin'
        ]);

        \App\Models\Depo::factory(3)->create();
        \App\Models\Customer::factory(5)->create();
    }
}
