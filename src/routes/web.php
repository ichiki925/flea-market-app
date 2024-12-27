<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;



// 商品一覧ページ（未認証でも閲覧可能）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// マイリストページ（認証済みユーザーのみ）
Route::get('/mylist', [ItemController::class, 'mylist'])->middleware('auth')->name('items.mylist');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

