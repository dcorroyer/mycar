<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory()->create([
                'firstname' => 'Admin',
                'lastname' => 'Mycar',
                'email' => 'admin@mycar.local',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
