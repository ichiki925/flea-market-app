<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Like;
use App\Models\SoldItem;


class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            UserSeeder::class,
            ProfileSeeder::class,
            ConditionSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            CategoryItemSeeder::class,
        ]);


    }
}
