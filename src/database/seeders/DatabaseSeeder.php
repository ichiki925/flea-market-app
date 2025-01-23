<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Purchase;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call(UserSeeder::class);

        $this->call([
            CategorySeeder::class,
            ConditionSeeder::class,
            ItemSeeder::class,
        ]);

        User::factory(5)->create()->each(function ($user) {
            $items = Item::factory(5)->create([
                'user_id' => $user->id,
                'condition_id' => Condition::inRandomOrder()->first()->id,
            ]);

            $items->each(function ($item) use ($user) {
                Comment::factory(2)->create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                ]);

                if (!Like::where('item_id', $item->id)->where('user_id', $user->id)->exists()) {
                    Like::factory()->create([
                        'item_id' => $item->id,
                        'user_id' => $user->id,
                    ]);
                }
            });
        });

        if (Item::exists() && User::exists()) {
            $users = User::pluck('id')->toArray();
            $items = Item::pluck('id')->toArray();

            foreach (array_slice($items, 0, 5) as $itemId) {
                $buyerId = $users[array_rand($users)];

                if (!Purchase::where('item_id', $itemId)->where('buyer_id', $buyerId)->exists()) {
                    Purchase::factory()->create([
                        'item_id' => $itemId,
                        'buyer_id' => $buyerId,
                        'payment_method' => 'card',
                    ]);
                }
            }
        }


    }
}
