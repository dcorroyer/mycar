<?php

namespace Database\Factories;

use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicule>
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
        $this->faker->addProvider(new Fakecar($this->faker));
        $vehicle = $this->faker->vehicleArray();

        return [
            'type'           => fake()->randomElement(['car', 'motorcycle', 'scooter']),
            'identification' => $this->faker->vehicleRegistration('[A-Z]{2}-[0-9]{3}-[A-Z]{2}'),
            'brand'          => $vehicle['brand'],
            'model'          => $vehicle['model'],
            'modelyear'      => $this->faker->biasedNumberBetween(1990, date('Y')),
        ];
    }
}
