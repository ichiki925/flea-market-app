<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\RegisterResponse;
use App\Http\Responses\LoginResponse;



class FortifyServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(RegisterRequest::class, function () {
            return new RegisterRequest();
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);


        $this->app->singleton(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            \App\Http\Responses\RegisterResponse::class
        );

        $this->app->bind(
            \Laravel\Fortify\Http\Requests\LoginRequest::class,
            \App\Http\Requests\LoginRequest::class
        );


        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            LoginResponse::class
        );

    }


    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });




        Fortify::authenticateUsing(function (LoginRequest $request) {
            \Log::info('Login request received', $request->only('email', 'password'));

            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                \Log::info('Login successful', ['email' => $credentials['email']]);
                return Auth::user();
            }

            \Log::warning('Login failed', ['email' => $credentials['email']]);
            return null;
        });



        Fortify::redirects('register', '/login');
        Fortify::redirects('login', '/mylist');
        Fortify::redirects('logout', '/');


        RateLimiter::for('login', function (Request $request) {
            $login = (string) $request->input('login');
            return Limit::perMinute(10)->by($login . $request->ip());
        });

    }



}
