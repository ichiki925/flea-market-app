<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Condition;
use Illuminate\Support\Facades\DB;



class ConditionSeeder extends Seeder
{
    public function run()
    {
        $conditions = [
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態が悪い',
        ];

        foreach ($conditions as $condition) {
            Condition::firstOrCreate(['condition' => $condition]);
        }
    }
}
