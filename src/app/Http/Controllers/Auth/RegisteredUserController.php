<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Http\Controllers\Controller;

class RegisteredUserController extends Controller
{
    protected $createNewUser;

    public function __construct(CreateNewUser $createNewUser)
    {
        $this->createNewUser = $createNewUser;
    }

    public function store(Request $request, RegisterResponse $response)
    {
        // ユーザー作成
        $this->createNewUser->create($request->all());

        // 自動ログインを無効化し、ログイン画面にリダイレクト
        return $response->toResponse($request);
    }
}
