<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
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
    //
    $this->app->instance(LoginResponse::class, new class implements LoginResponse{
      public function toResponse($request) {
        $user = Auth::user();

        //dd(['user_type'=>$user->user_type, 'status'=>$user->status]);

        if ($user->user_type===2 && $user->status===1) {
          return $request->wantsJson()
            ? response()->json(['redirect' => route('elegir-suscripcion') ])
            : redirect()->route('elegir-suscripcion');
        }

        return $request->wantsJson()
          ? response()->json(['two_factor' => false, 'redirect' => config('fortify.home') ])
          : redirect()->intended( config('fortify.home') );
      }
    });
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
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
    Fortify::registerView(function(){
      return view('auth.registro');
    });
    Fortify::loginView(function(){
      return view('auth.login');
    });
    Fortify::requestPasswordResetLinkView(function(){
      return view('auth.forgot-password');
    });
    Fortify::resetPasswordView(function($request){
      return view('auth.reset-password', ['request',$request]);
    });
  }
}
