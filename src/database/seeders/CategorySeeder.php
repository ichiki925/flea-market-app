<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('categories')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $categories = [
            ['category' => 'ファッション'],
            ['category' => '家電'],
            ['category' => 'インテリア'],
            ['category' => 'レディース'],
            ['category' => 'メンズ'],
            ['category' => 'コスメ'],
            ['category' => '本'],
            ['category' => 'ゲーム'],
            ['category' => 'スポーツ'],
            ['category' => 'キッチン'],
            ['category' => 'ハンドメイド'],
            ['category' => 'アクセサリー'],
            ['category' => 'おもちゃ'],
            ['category' => 'ベビー・キッズ'],
        ];

        $now = now();
        foreach ($categories as &$category) {
            $category['created_at'] = $now;
            $category['updated_at'] = $now;
        }

        DB::table('categories')->insert($categories);
    }
}
