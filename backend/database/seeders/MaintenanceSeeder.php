<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use App\Models\Vehicule;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $vehicules = Vehicule::all();

        foreach ($vehicules as $vehicule) {
            Maintenance::factory(mt_rand(2, 3))->create([
                'vehicule_id' => $vehicule->id,
            ]);
        }
    }
}
