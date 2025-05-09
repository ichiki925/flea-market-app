<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'item_id',
    ];
}
