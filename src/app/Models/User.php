<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }


    public function soldItems()
    {
        return $this->hasMany(SoldItem::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function isFirstLogin()
    {
        return $this->created_at->eq($this->updated_at);
    }


    public function purchases()
    {
        return $this->hasMany(\App\Models\SoldItem::class);
    }

}
