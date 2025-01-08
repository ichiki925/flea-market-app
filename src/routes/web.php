<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;



//商品
Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist')->middleware('auth');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.detail');

// いいね機能
Route::post('/likes/toggle/{itemId}', [LikeController::class, 'toggleLike'])->name('likes.toggle')->middleware('auth');
Route::post('/likes/guest/{itemId}', [LikeController::class, 'guestLike'])->name('likes.guest');

// コメント機能
Route::post('/comments', [ItemController::class, 'storeComment'])->name('comments.store')->middleware('auth');


// 商品購入ページ
Route::prefix('purchase')->middleware('auth')->group(function () {
    Route::get('/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::post('/process-payment', [PurchaseController::class, 'processPayment'])->name('purchase.processPayment');
});




// ログアウト
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// マイページ
Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage')->middleware('auth');
Route::post('/mypage/update', [MyPageController::class, 'update'])->name('mypage.update');

// プロフィール
Route::get('/mypage/profile', [MyPageController::class, 'editProfile'])->name('mypage.profile')->middleware('auth');




