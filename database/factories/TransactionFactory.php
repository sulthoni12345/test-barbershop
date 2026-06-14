<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $service = Service::inRandomOrder()->first()
            ?? Service::factory()->create();

        $qty   = fake()->numberBetween(1, 5);
        $total = $service->price * $qty;

        return [
            'service_id' => $service->id,
            'qty'        => $qty,
            'total'      => $total,
        ];
    }
}
