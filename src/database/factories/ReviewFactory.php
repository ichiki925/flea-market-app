<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'reviewer_id' => User::factory(),
            'reviewee_id' => User::factory(),
            'item_id'     => Item::factory(),
            'rating'      => $this->faker->numberBetween(1, 5),
        ];
    }
}
