<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('conditions')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $conditions = [
            ['condition' => '良好'],
            ['condition' => '目立った傷や汚れなし'],
            ['condition' => 'やや傷や汚れあり'],
            ['condition' => '状態が悪い'],
        ];

        $now = now();
        foreach ($conditions as &$condition) {
            $condition['created_at'] = $now;
            $condition['updated_at'] = $now;
        }

        DB::table('conditions')->insert($conditions);
    }
}
