<?php

namespace Modules\Course\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseCategory;
use Modules\Course\Models\CourseEnrollment;
use Modules\Course\Policies\CourseCategoryPolicy;
use Modules\Course\Policies\CourseEnrollmentPolicy;
use Modules\Course\Policies\CoursePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Course::class => CoursePolicy::class,
        CourseCategory::class => CourseCategoryPolicy::class,
        CourseEnrollment::class => CourseEnrollmentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
