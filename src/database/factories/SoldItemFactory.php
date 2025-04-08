<?php

namespace Database\Factories;

use App\Models\SoldItem;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoldItemFactory extends Factory
{
    protected $model = SoldItem::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'sending_postcode' => $this->faker->postcode(),
            'sending_address' => $this->faker->address(),
            'sending_building' => $this->faker->secondaryAddress(),
            'payment_method' => $this->faker->randomElement(['card', 'convenience_store']),
        ];
    }
}
