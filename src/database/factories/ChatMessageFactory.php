<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Item;

class ChatMessageFactory extends Factory
{

    public function definition()
    {
        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory(),
            'message' => $this->faker->sentence(),
            'image_path' => null,
            'read_at' => null,
        ];
    }
}
