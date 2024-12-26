<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{

    public function run()
    {
        // 変更必要
        User::create([
            'name' => 'testuser1',
            'email' => 'test1@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Password1234'),
            'profile_image' => null,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区テスト町1-1-1',
            'building' => 'テストビル101号室',
        ]);

        User::create([
            'name' => 'testuser2',
            'email' => 'test2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Password5678'),
            'profile_image' => null,
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市テスト区2-2-2',
            'building' => 'テストマンション202号室',
        ]);
    }
}
