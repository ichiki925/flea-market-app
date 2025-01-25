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
        // 外部キー制約を無効化してデータをリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('items')->truncate();
        DB::table('item_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // データ挿入
        DB::table('items')->insert([
            [
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'images/ArmaniMensClock.jpg',
                'condition_id' => 1,
                'brand' => 'Armani',
            ],
            [
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'user_id' => 1,
                'status' => 'sold',
                'item_image' => 'images/HDD_Hard_Disk.jpg',
                'condition_id' => 2,
                'brand' => null,
            ],
            [
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束セット',
                'price' => 300,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'images/iLoveIMG_d.jpg',
                'condition_id' => 3,
                'brand' => null,
            ],
            [
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'images/LeatherShoesProductPhoto.jpg',
                'condition_id' => 4,
                'brand' => null,
            ],
            [
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'images/LivingRoomLaptop.jpg',
                'condition_id' => 1,
                'brand' => null,
            ],
            [
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'images/MusicMic4632231.jpg',
                'condition_id' => 2,
                'brand' => null,
            ],
            [
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'images/PurseFashionPocket.jpg',
                'condition_id' => 3,
                'brand' => null,
            ],
            [
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'images/TumblerSouvenir.jpg',
                'condition_id' => 4,
                'brand' => null,
            ],
            [
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'images/WaitressWithCoffeeGrinder.jpg',
                'condition_id' => 1,
                'brand' => null,
            ],
            [
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'images/MakeupSet.jpg',
                'condition_id' => 2,
                'brand' => null,
            ],
        ]);

        DB::table('item_categories')->insert([
            ['item_id' => 1, 'category_id' => 1],
            ['item_id' => 1, 'category_id' => 5],
            ['item_id' => 2, 'category_id' => 2],
            ['item_id' => 3, 'category_id' => 10],
            ['item_id' => 4, 'category_id' => 1],
            ['item_id' => 5, 'category_id' => 2],
            ['item_id' => 6, 'category_id' => 2],
            ['item_id' => 7, 'category_id' => 1],
            ['item_id' => 8, 'category_id' => 10],
            ['item_id' => 9, 'category_id' => 10],
            ['item_id' => 10, 'category_id' => 6],
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
