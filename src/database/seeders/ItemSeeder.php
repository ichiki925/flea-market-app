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
        $items = [
            [
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'category_id' => DB::table('categories')->where('name', 'ファッション')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', '良好')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'user_id' => 1,
            ],
            [
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'category_id' => DB::table('categories')->where('name', '家電')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', '目立った傷や汚れなし')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'user_id' => 1,
            ],
            [
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'category_id' => DB::table('categories')->where('name', 'キッチン')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', 'やや傷や汚れあり')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'user_id' => 1,
            ],
            [
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'category_id' => DB::table('categories')->where('name', 'ファッション')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', '状態が悪い')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'user_id' => 1,
            ],
            [
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'category_id' => DB::table('categories')->where('name', '家電')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', '良好')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'user_id' => 1,
            ],
            [
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'category_id' => DB::table('categories')->where('name', '家電')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', '目立った傷や汚れなし')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'user_id' => 1,
            ],
            [
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'category_id' => DB::table('categories')->where('name', 'ファッション')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', 'やや傷や汚れあり')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'user_id' => 1,
            ],
            [
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'category_id' => DB::table('categories')->where('name', 'キッチン')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', '状態が悪い')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'user_id' => 1,
            ],
            [
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'category_id' => DB::table('categories')->where('name', 'キッチン')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', '良好')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'user_id' => 1,
            ],
            [
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'category_id' => DB::table('categories')->where('name', 'コスメ')->value('id'),
                'condition_id' => DB::table('conditions')->where('name', '目立った傷や汚れなし')->value('id'),
                'item_image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'user_id' => 1,
            ],
        ];

        foreach ($items as &$item) {
            // 外部URLから画像をダウンロードして保存
            $filename = basename($item['item_image_url']); // URLからファイル名を取得
            $directory = 'items'; // 保存先ディレクトリ

            $path = $this->storeImageFromUrl($item['item_image_url'], $directory, $filename);

            // 保存した画像のパスを`item_image`として設定
            $item['item_image'] = $path;

            // URLは不要なので削除
            unset($item['item_image_url']);
        }

        // データ挿入
        DB::table('items')->insert($items);
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
