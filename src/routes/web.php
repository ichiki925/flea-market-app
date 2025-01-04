<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;




Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist')->middleware('auth');

// 商品詳細ページ
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.detail');

Route::post('/likes/toggle/{itemId}', [LikeController::class, 'toggleLike'])->name('likes.toggle')->middleware('auth');
Route::post('/likes/guest/{itemId}', [LikeController::class, 'guestLike'])->name('likes.guest');

Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase')->middleware('auth');




Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::post('/mypage/update', [MyPageController::class, 'update'])->name('mypage.update');


// ビュー作成用

Route::get('/mypage', function () {
    return view('mypage');
});

Route::get('/sell', function () {
    return view('sell');
});





Route::get('/purchase/address', function () {
    return view('address');
});
