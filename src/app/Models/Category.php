<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // リレーション: Categoryは複数のItemに属する
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_categories', 'category_id', 'item_id')
                    ->withTimestamps(); // 中間テーブルのタイムスタンプも利用
    }
}
