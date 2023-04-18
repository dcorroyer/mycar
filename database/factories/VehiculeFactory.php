<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicule;
use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicule>
 */
class VehiculeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = new Fakecar($this->faker);
        $vehicle = $faker->vehicleArray();

        return [
            'type' => fake()->randomElement(['car', 'motorcycle', 'scooter']),
            'identification' => $faker->vehicleRegistration('[A-Z]{2}-[0-9]{3}-[A-Z]{2}'),
            'brand' => $vehicle['brand'],
            'model' => $vehicle['model'],
            'modelyear' => $this->faker->biasedNumberBetween(1990, date('Y')),
        ];
    }

    /**
     * @param User $user
     *
     * @return VehiculeFactory
     */
    public function owner(User $user): VehiculeFactory
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }
}
