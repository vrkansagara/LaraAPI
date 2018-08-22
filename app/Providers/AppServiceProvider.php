<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Cashier::useCurrency('gbp', '£');
        DB::listen(function ($query) {
            // $query->sql
            // $query->bindings
            // $query->time
        });

//        \Braintree_Configuration::environment(config('services.braintree.environment'));
//        \Braintree_Configuration::merchantId(config('services.braintree.merchant_id'));
//        \Braintree_Configuration::publicKey(config('services.braintree.public_key'));
//        \Braintree_Configuration::privateKey(config('services.braintree.private_key'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
        $this->app->register(RepositoryServiceProvider::class);
    }
}
