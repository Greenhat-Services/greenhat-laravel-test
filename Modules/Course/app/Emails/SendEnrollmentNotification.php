<?php

namespace Modules\Course\Emails;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsJob;
use Modules\Course\Emails\EnrollmentConfirmationEmail;
use Modules\Course\Models\Course;
use Modules\Employee\Models\Employee;

/**
 * queued job to send the enrollment confirmation email to the employee
 */

class SendEnrollmentNotification implements ShouldQueue
{
    use AsAction, AsJob;

    public function handle(Employee $employee, Course $course): void
    {
        Mail::queue(new EnrollmentConfirmationEmail($employee, $course));
    }
}