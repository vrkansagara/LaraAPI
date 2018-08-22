<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    protected $namespaceApi = 'App\Http\Controllers\Api';

    protected $namespaceApiV1 = 'App\Http\Controllers\Api\V1';

    protected $namespaceApiV2 = 'App\Http\Controllers\Api\V2';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapV1ApiRoutes();

//        $this->mapV2ApiRoutes();

//        $this->mapWebRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespaceApi)
             ->group(base_path('routes/api.php'));
    }

    protected function mapV1ApiRoutes()
    {
        Route::prefix('api/v1')
             ->middleware('api')
             ->namespace($this->namespaceApiV1)
             ->group(base_path('routes/api/v1.php'));
    }

    protected function mapV2ApiRoutes()
    {
        Route::prefix('api/v2')
            ->middleware('api')
            ->namespace($this->namespaceApiV2)
            ->group(base_path('routes/api/v2.php'));
    }
}
