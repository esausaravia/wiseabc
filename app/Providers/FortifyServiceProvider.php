<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Fortify;

/*
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
*/

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
        $this->app->instance(LoginResponse::class, new class implements LoginResponse{
          public function toResponse($request) {

            return $request->wantsJson()
              ? response()->json(['two_factor' => false, 'redirect' => config('fortify.home') ])
              : redirect()->intended( config('fortify.home') );
          }
        });*/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Password::defaults(function () {
            $ruleProd = Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();

            return $this->app->isProduction() ? $ruleProd : Password::min(8);
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        /*
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
          $email = (string) $request->email;

          return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
          return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
        */
        Fortify::registerView(function (Request $request) {

            $horarios = [1 => []];
            for ($h = 7; $h < 22; $h++) {
                $horarios[1][] = $h.':00';
            }

            return view('auth.registro', [
                'horarios' => $horarios[1],
            ]);
        });
        Fortify::loginView(function () {
            return view('auth.login');
        });
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });
        Fortify::verifyEmailView(function ($request) {
            return view('auth.verify-email', ['request' => $request]);
        });
    }
}
