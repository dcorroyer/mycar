<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Database\Seeder;

class VehiculeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        Vehicule::factory(3)->create([
            'user_id' => User::where('email', 'admin@mycar.local')->first(),
        ]);
    }
}
