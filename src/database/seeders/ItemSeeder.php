<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;


class ItemSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('items')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');





        $now = now();

        DB::table('items')->insert([
            [
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'user_id' => 1,
                'status' => 'available',
                'img_url' => 'images/ArmaniMensClock.jpg',
                'condition_id' => 1,
                'brand' => 'Armani',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'user_id' => 1,
                'status' => 'sold',
                'img_url' => 'images/HDD_Hard_Disk.jpg',
                'condition_id' => 2,
                'brand' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束セット',
                'price' => 300,
                'user_id' => 1,
                'status' => 'available',
                'img_url' => 'images/iLoveIMG_d.jpg',
                'condition_id' => 3,
                'brand' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'user_id' => 1,
                'status' => 'available',
                'img_url' => 'images/LeatherShoesProductPhoto.jpg',
                'condition_id' => 4,
                'brand' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'user_id' => 1,
                'status' => 'available',
                'img_url' => 'images/LivingRoomLaptop.jpg',
                'condition_id' => 1,
                'brand' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'user_id' => 1,
                'status' => 'available',
                'img_url' => 'images/MusicMic4632231.jpg',
                'condition_id' => 2,
                'brand' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'user_id' => 1,
                'status' => 'available',
                'img_url' => 'images/PurseFashionPocket.jpg',
                'condition_id' => 3,
                'brand' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'user_id' => 1,
                'status' => 'available',
                'img_url' => 'images/TumblerSouvenir.jpg',
                'condition_id' => 4,
                'brand' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'user_id' => 1,
                'status' => 'available',
                'img_url' => 'images/WaitressWithCoffeeGrinder.jpg',
                'condition_id' => 1,
                'brand' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'user_id' => 1,
                'status' => 'available',
                'img_url' => 'images/MakeupSet.jpg',
                'condition_id' => 2,
                'brand' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);


    }

    private function storeImageFromUrl($url, $directory, $filename)
    {
        $response = Http::get($url);

        if ($response->successful()) {
            $path = $directory . '/' . $filename;
            Storage::disk('public')->put($path, $response->body());
            return $path;
        }

        throw new \Exception("画像のダウンロードに失敗しました: {$url}");
    }
}
