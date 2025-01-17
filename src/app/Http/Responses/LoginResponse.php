<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // 初回ログイン判定
        if ($user->created_at->eq($user->updated_at)) {
            return redirect('/mypage/profile/create'); // 初回ログイン時
        }

        return redirect('/mylist'); // 通常ログイン時
    }
}
