<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'buyer_id',
        'address',
        'building',
        'postal_code',
        'payment_method',
    ];

    // リレーション: Purchaseは1つのItemに属する
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // リレーション: Purchaseは1人のBuyer(User)に属する
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
