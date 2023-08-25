<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Maintenance;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $maintenances = Maintenance::all();

        foreach ($maintenances as $maintenance) {
            Invoice::factory(mt_rand(2, 3))->create([
                'maintenance_id' => $maintenance->id,
                'vehicule_id' => $maintenance->vehicule_id,
            ]);
        }
    }
}
