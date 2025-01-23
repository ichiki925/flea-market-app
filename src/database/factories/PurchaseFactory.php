<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'item_id' => \App\Models\Item::inRandomOrder()->first()->id,
            'buyer_id' => \App\Models\User::inRandomOrder()->first()->id,
            'address' => $this->faker->address(),
            'building' => $this->faker->secondaryAddress(),
            'postal_code' => $this->faker->postcode(),
            'payment_method' => $this->faker->randomElement(['card', 'convenience_store']),
        ];
    }
}
