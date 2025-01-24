<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');



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

    // 決済成功・キャンセル用のルート
    Route::get('/success/{item_id}', [PurchaseController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/cancel/{item_id}', [PurchaseController::class, 'paymentCancel'])->name('payment.cancel');
});


// 住所変更
Route::get('/address/edit', [MyPageController::class, 'editAddress'])->name('mypage.editAddress')->middleware('auth');
Route::post('/address/update', [MyPageController::class, 'updateAddress'])->name('mypage.updateAddress')->middleware('auth');


// 商品出品
Route::middleware(['auth'])->group(function () {
    Route::get('/sell', [SellController::class, 'index'])->name('sell.index');
    Route::post('/sell/store', [SellController::class, 'store'])->name('sell.store');
});


// マイページ商品出品
Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage')->middleware('auth');
// マイページ商品購入
Route::get('/mypage/purchases', [MyPageController::class, 'purchases'])->name('mypage.purchases')->middleware('auth');




Route::middleware(['auth'])->group(function () {
    // 新規登録
    Route::get('/mypage/profile/create', [MyPageController::class, 'createProfile'])->name('mypage.create');
    Route::post('/mypage/profile', [MyPageController::class, 'storeProfile'])->name('mypage.store');

    // 編集
    Route::get('/mypage/profile', [MyPageController::class, 'editProfile'])->name('mypage.profile');
    Route::put('/mypage/profile', [MyPageController::class, 'updateProfile'])->name('mypage.update');
});


// ログアウト
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');



Route::get('/some-endpoint', function () {
    return response('OK', 200);
});
