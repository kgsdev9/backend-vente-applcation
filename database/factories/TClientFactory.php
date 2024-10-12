<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'codeclient' => "CC" .rand(100, 500),
            'libtiers' => $this->faker->unique()->name(),
            'adressegeo' => $this->faker->unique()->address(),
            'fax' => $this->faker->unique()->phoneNumber(),
            'telephone' => $this->faker->unique()->phoneNumber(),
            'email' => $this->faker->unique()->email(),
            'numerocomtribuabe' => rand(100009898989, 50000989899),
            'adressepostale' => $this->faker->unique()->address(),
            'numerodecompte' => rand(100009898989, 50000989899),
            'capital' => rand(100009898989, 50000989899),
            'sitelivraison' => $this->faker->unique()->address(),
            'tregimefiscal_id' => rand(1, 3),
            'tcodedevise_id' => rand(1, 3),
        ];
    }
}
