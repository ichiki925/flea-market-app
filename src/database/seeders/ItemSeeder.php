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
                'item_image' => 'items/Armani+Mens+Clock.jpg',
                'condition_id' => 1,
            ],
            [
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'user_id' => 1,
                'status' => 'sold',
                'item_image' => 'items/HDD+Hard+Disk.jpg',
                'condition_id' => 2,
            ],
            [
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束セット',
                'price' => 300,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'items/iLoveIMG+d.jpg',
                'condition_id' => 3,
            ],
            [
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'items/Leather+Shoes+Product+Photo.jpg',
                'condition_id' => 4,
            ],
            [
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'items/Living+Room+Laptop.jpg',
                'condition_id' => 1,
            ],
            [
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'items/Music+Mic+4632231.jpg',
                'condition_id' => 2,
            ],
            [
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'items/Purse+fashion+pocket.jpg',
                'condition_id' => 3,
            ],
            [
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'items/Tumbler+souvenir.jpg',
                'condition_id' => 4,
            ],
            [
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'items/Waitress+with+Coffee+Grinder.jpg',
                'condition_id' => 1,
            ],
            [
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'user_id' => 1,
                'status' => 'available',
                'item_image' => 'items/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition_id' => 2,
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
        // foreach ($items as &$item) {
        //     // 外部URLから画像をダウンロードして保存
        //     $filename = basename($item['item_image_url']); // URLからファイル名を取得
        //     $directory = 'items'; // 保存先ディレクトリ

        //     $path = $this->storeImageFromUrl($item['item_image_url'], $directory, $filename);

        //     // 保存した画像のパスを`item_image`として設定
        //     $item['item_image'] = $path;

        //     // URLは不要なので削除
        //     unset($item['item_image_url']);
        // }


    }

    private function storeImageFromUrl($url, $directory, $filename)
    {
        $response = Http::get($url);

        if ($response->successful()) {
            $path = $directory . '/' . $filename;
            Storage::disk('public')->put($path, $response->body());
            return $path; // 保存されたパスを返す
        }

        throw new \Exception("画像のダウンロードに失敗しました: {$url}");
    }
}
