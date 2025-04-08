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
        return [
            'user_id' => User::factory(),
            'condition_id' => function () {
                $condition = Condition::inRandomOrder()->first();
                if ($condition) {
                    return $condition->id;
                }
                return Condition::factory()->create()->id;
            },
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'brand' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'img_url' => null,
            'status' => 'available',
        ];
    }
}
