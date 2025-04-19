<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile;


class ProfileSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('profiles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = User::all();

        foreach ($users as $user) {
            $user->profile()->create([
                'img_url' => 'img/default-profile.png',
                'postcode' => '000-0000',
                'address' => 'ダミー住所',
                'building' => 'ダミービル101',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }


}
