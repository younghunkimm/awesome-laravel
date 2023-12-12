<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Rules\Password as PasswordRule;

class PasswordServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Password::defaults(function() {
            $rule = Password::min(8);

            // 환경이 production인 경우에만 전부 검사하고, 그렇지 않다면 최소 글자수만 충족되도록 검사한다.
            return $this->app->isProduction()
            ? $rule->letters()->mixedCase()->numbers()->symbols()->uncompromised()
            : $rule; // Password:min(8)->rules([new PasswordRule()]);
        });
    }
}
