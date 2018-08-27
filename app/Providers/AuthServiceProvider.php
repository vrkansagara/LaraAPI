<?php

namespace App\Providers;

use App\Entities\Todo;
use App\Policies\TodoPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Todo::class => TodoPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        Passport::routes();

        Passport::tokensExpireIn(now()->addDays(config('TOKEN_EXPIRE')));

        Passport::refreshTokensExpireIn(now()->addDays(config('REFRESH_TOKEN_EXPIRE')));

        // Implicit Grant Tokens
//        Passport::enableImplicitGrant();

    }
}
