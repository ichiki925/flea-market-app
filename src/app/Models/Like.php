<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
    ];

    // リレーション: Likeは1つのItemに属する
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // リレーション: Likeは1人のUserに属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
