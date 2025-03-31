<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory;

    public $incrementing = false; // 主キーが自動増分ではない

    protected $fillable = [
        'category_id',
        'item_id',
    ];
}
