<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProfileSeeder extends Seeder
{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('profiles')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        DB::table('profiles')->insert([
            [
                'user_id' => 1,
                'img_url' => 'images/profile1.jpg',
                'postcode' => '100-0001',
                'address' => '東京都千代田区千代田1-1',
                'building' => '皇居タワー101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'img_url' => 'images/profile2.jpg',
                'postcode' => '530-0001',
                'address' => '大阪府大阪市北区梅田1-1',
                'building' => '梅田スカイビル202',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

    }
}
