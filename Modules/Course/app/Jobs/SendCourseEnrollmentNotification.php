<?php

namespace Modules\Course\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Course\Emails\CourseEnrollmentConfirmationEmail;
use Modules\Course\Models\CourseEnrollment;

class SendCourseEnrollmentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public CourseEnrollment $enrollment;

    public function __construct(CourseEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function handle(): void
    {
        // Send email to the employee
        Mail::to($this->enrollment->employee->user->email)
            ->send(new CourseEnrollmentConfirmationEmail($this->enrollment));
    }
}
