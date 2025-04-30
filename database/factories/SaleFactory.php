<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = \App\Models\Sale::class;

    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'amount'      => $this->faker->randomFloat(2, 10, 500),
            'sold_at'     => $this->faker->dateTimeBetween('-1 month'),
        ];
    }
}
