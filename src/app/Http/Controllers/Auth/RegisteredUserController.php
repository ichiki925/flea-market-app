<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Http\Controllers\Controller;

class RegisteredUserController extends Controller
{
    protected $createNewUser;

    public function create()
    {
        return view('auth.register');
    }

    public function __construct(CreateNewUser $createNewUser)
    {
        $this->createNewUser = $createNewUser;
    }

    public function store(RegisterRequest $request, RegisterResponse $response)
    {
        $this->createNewUser->create($request->validated());

        return $response->toResponse($request);
    }
}
