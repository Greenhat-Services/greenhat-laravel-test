<?php

namespace Modules\Course\Observers;

use Modules\Course\Jobs\SendCourseEnrollmentNotification;
use Modules\Course\Models\CourseEnrollment;

class CourseEnrollmentObserver
{
    public function created(CourseEnrollment $enrollment): void
    {
        // Dispatch notification job when enrollment is created
        SendCourseEnrollmentNotification::dispatch($enrollment);
    }
}
