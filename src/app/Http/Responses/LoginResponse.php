<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->created_at->eq($user->updated_at)) {
            return redirect('/mypage/profile/create');
        }

        return redirect('/mylist');
    }
}
