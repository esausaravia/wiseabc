<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
        Blade::directive('money', function (string $expression) {
            return "<?php echo number_format( $expression, 2, '.', ',') ?>";
        });

        Blade::directive('localhr', function (string $expression) {
            $user = Auth::user();
            if (is_object($user)) {
                $timezone = $user->timezone;
            } else {
                $timezone = '-0600';
            }
            $return = "<?php echo \Illuminate\Support\Carbon::parse( {$expression} )->setTimezone('{$timezone}')->format('H:i') ?>";
            Log::debug('Blade directive localhr ', [
                'expression' => $expression,
                'return' => $return,
            ]);

            return $return;
        });
    }
}
