<?php

namespace Modules\Course\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Course\Models\CourseEnrollment;
use Modules\Course\Observers\CourseEnrollmentObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Register observers.
     */
    public function boot(): void
    {
        parent::boot();

        CourseEnrollment::observe(CourseEnrollmentObserver::class);
    }

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void
    {
        //
    }
}
