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
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;



class FortifyServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(RegisterRequest::class, function () {
            return new RegisterRequest();
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);

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
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // メール認証確認
                if (!$user->hasVerifiedEmail()) {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => __('auth.email_not_verified'),
                    ]);
                }

                return $user;
            }

            // 認証失敗時のエラーメッセージ
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        });



        // リダイレクト先を設定
        Fortify::redirects('register', '/login');
        Fortify::redirects('login', '/mylist');
        Fortify::redirects('logout', '/');

        // ログインのレートリミット
        RateLimiter::for('login', function (Request $request) {
            $login = (string) $request->input('login');
            return Limit::perMinute(10)->by($login . $request->ip());
        });

    }



}
