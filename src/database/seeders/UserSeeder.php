<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{

    public function run()
    {
        // 外部キー制約を無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 既存データを削除
        DB::table('users')->truncate();

        // 外部キー制約を再度有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 新しいデータを挿入
        User::create([
            'id' => 1,
            'name' => 'Test User 1',
            'email' => 'test1@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区道玄坂1-2-3',
            'building' => 'マンション101号室',
            'profile_image' => 'user.png',
        ]);

        User::create([
            'id' => 2,
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市北区梅田1-2-3',
            'building' => 'オフィスビル201号室',
            'profile_image' => 'user.png',
        ]);
    }
}
