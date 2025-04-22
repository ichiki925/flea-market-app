<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        $condition = Condition::first() ?? Condition::create(['condition' => '良好']);

        return [
            'user_id' => User::factory(),
            'condition_id' => $condition->id,
            'name' => $this->faker->lexify('商品名????'),
            'price' => $this->faker->numberBetween(1000, 10000),
            'brand' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'img_url' => null,
            'status' => 'available',
        ];
    }
}
