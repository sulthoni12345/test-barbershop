<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $services = [
            'Cukur Rambut', 'Cukur Jenggot', 'Keramas',
            'Creambath', 'Blow Dry', 'Coloring Rambut',
        ];

        return [
            'name'  => fake()->unique()->randomElement($services),
            'price' => fake()->randomElement([15000, 20000, 25000, 35000, 50000]),
        ];
    }
}
