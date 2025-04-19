<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;
use App\Models\SoldItem;
use App\Models\ChatMessage;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'description',
        'price',
        'user_id',
        'status',
        'img_url',
        'condition_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_items', 'item_id', 'category_id')
                    ->withTimestamps();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function soldItem()
    {
        return $this->hasOne(SoldItem::class);
    }

    public function isSold()
    {
        return $this->status === 'sold';
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function soldItems()
    {
        return $this->hasMany(SoldItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}

