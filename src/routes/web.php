<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\MyPageController;



// 商品一覧ページ（未認証でも閲覧可能）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// マイリストページ（認証済みユーザーのみ）
Route::get('/mylist', [ItemController::class, 'mylist'])->middleware('auth')->name('items.mylist');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::post('/mypage/update', [MyPageController::class, 'update'])->name('mypage.update');

// ビュー作成用

Route::get('/mypage', function () {
    return view('mypage');
});

Route::get('/sell', function () {
    return view('sell');
});

Route::get('/item', function () {
    return view('item_detail');
});

Route::get('/purchase', function () {
    return view('purchase');
});

Route::get('/purchase/address', function () {
    return view('address');
});
