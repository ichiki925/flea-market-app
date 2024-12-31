<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadImagesCommand extends Command
{

    protected $signature = 'download:images';


    protected $description = 'Download images from external URLs and save them locally';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $this->info('画像のダウンロードを開始します...');

        // itemsテーブルから外部URLの画像を取得
        $items = DB::table('items')
            ->where('item_image', 'like', 'http%') // 外部URLのみ対象
            ->get();

        foreach ($items as $item) {
            try {
                // 外部URLから画像を取得
                $response = Http::get($item->item_image);

                if ($response->successful()) {
                    // ファイル名を取得
                    $filename = basename($item->item_image);

                    // 保存先のパスを設定
                    $path = 'items/' . $filename;

                    // 画像をローカルストレージに保存
                    Storage::disk('public')->put($path, $response->body());

                    // データベースのitem_imageを更新
                    DB::table('items')
                        ->where('id', $item->id)
                        ->update(['item_image' => $path]);

                    $this->info("画像を保存しました: {$path}");
                } else {
                    $this->error("画像の取得に失敗しました: {$item->item_image}");
                }
            } catch (\Exception $e) {
                $this->error("エラー: {$e->getMessage()}");
            }
        }

        $this->info('画像のダウンロードが完了しました。');
    }
}
