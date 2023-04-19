<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class MaintenanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['maintenance', 'restoration', 'repair']),
            'date' => Carbon::now()->format('Y-m-d'),
            'amount' => fake()->randomFloat(2, 50, 1500),
            'description' => fake()->text(),
        ];
    }

    /**
     * @param Vehicule $vehicule
     *
     * @return MaintenanceFactory
     */
    public function vehicule(Vehicule $vehicule): MaintenanceFactory
    {
        return $this->state(fn () => [
            'vehicule_id' => $vehicule->id,
        ]);
    }
}
