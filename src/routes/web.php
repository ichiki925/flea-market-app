<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RatingController;





Route::get('/', [ItemController::class, 'redirectTop'])->name('index');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.detail');


Route::middleware(['auth'])->group(function () {

    // メール認証
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/mypage/profile');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送しました');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // マイページ関係
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');
    Route::get('/mypage/purchases', [MyPageController::class, 'purchases'])->name('mypage.purchases');
    Route::get('/mypage/trading', [MyPageController::class, 'trading'])->name('mypage.trading');
    Route::post('/rating/{item_id}', [RatingController::class, 'submitReview'])->name('rating.submit');


    // プロフィール
    Route::get('/mypage/profile/create', [MyPageController::class, 'createProfile'])->name('mypage.create');
    Route::post('/mypage/profile', [MyPageController::class, 'storeProfile'])->name('mypage.store');
    Route::get('/mypage/profile', [MyPageController::class, 'editProfile'])->name('mypage.profile');
    Route::put('/mypage/profile', [MyPageController::class, 'updateProfile'])->name('mypage.update');

    // 住所
    Route::get('/address/edit', [MyPageController::class, 'editAddress'])->name('mypage.editAddress');
    Route::post('/address/update', [MyPageController::class, 'updateAddress'])->name('mypage.updateAddress');

    // 売るページ
    Route::get('/sell', [SellController::class, 'index'])->name('sell.index');
    Route::post('/sell/store', [SellController::class, 'store'])->name('sell.store');

    // 取引チャット
    Route::get('/chat/{item}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{item}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/{item}/complete', [ChatController::class, 'complete'])->name('chat.complete');
    Route::post('/review/{item_id}', [ChatController::class, 'submitReview'])->name('review.submit');
    Route::post('/chat/complete/{item}', [ChatController::class, 'completeTransaction'])->name('chat.completeTransaction');

    // 編集フォーム表示（再入力用）
    Route::get('/chat/message/{message}/edit', [ChatController::class, 'edit'])->name('chat.edit');
    // 更新処理（再送信）
    Route::put('/chat/message/{message}', [ChatController::class, 'update'])->name('chat.update');
    // メッセージの削除
    Route::delete('/chat/message/{message}', [ChatController::class, 'destroy'])->name('chat.destroy');

    // マイリスト・コメント・いいね
    Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist');
    Route::post('/likes/toggle/{itemId}', [LikeController::class, 'toggleLike'])->name('likes.toggle');
    Route::post('/comments', [ItemController::class, 'storeComment'])->name('comments.store');

    // 購入系
    Route::prefix('purchase')->group(function () {
        Route::get('/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
        Route::post('/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
        Route::post('/process-payment', [PurchaseController::class, 'processPayment'])->name('purchase.processPayment');
        Route::get('/success/{item_id}', [PurchaseController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/cancel/{item_id}', [PurchaseController::class, 'paymentCancel'])->name('payment.cancel');
    });



    // ログアウト
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});


