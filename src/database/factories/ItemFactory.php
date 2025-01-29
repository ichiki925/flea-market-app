<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'brand' => $this->faker->optional()->company(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100, 50000),
            'user_id' => User::factory(),
            'status' => 'available',
            'item_image' => 'items/example.png',
            'condition_id' => Condition::factory(),
        ];
    }
}
