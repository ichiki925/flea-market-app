<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buyer_id',
        'item_id',
        'sending_postcode',
        'sending_address',
        'sending_building',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
