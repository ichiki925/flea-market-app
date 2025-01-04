<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // リレーション: Conditionは複数のItemを持つ
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
