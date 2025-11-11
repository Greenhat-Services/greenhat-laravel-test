<?php

namespace Modules\Course\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Middleware\SubstituteBindings;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Course';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapPrivateV1Routes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')->prefix('api')->name('api.')->group(module_path($this->name, '/routes/api.php'));
    }

    protected function mapPrivateV1Routes(): void
    {
        Route::middleware(['auth:sanctum', 'throttle:api', SubstituteBindings::class]) // throttle:api is the custom throttle middleware
            ->prefix('v1') // prefix for the routes
            ->group(module_path($this->name, '/routes/CourseEnrollment.v1.private.php'));
    }
}
