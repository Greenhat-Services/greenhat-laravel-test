<?php

namespace Modules\Course\Emails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Course\Models\Course;
use Modules\Employee\Models\Employee;

/**
 *  Mailable Class for the Enrollment Confirmation Email
 */

class EnrollmentConfirmationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Employee $employee,
        public Course $course
    ) {}

    /**
     * Build the message.
     */

    public function build(): self
    {
        return $this
            ->to($this->employee->user->email, $this->employee->user->name)
            ->subject('You are now enrolled in a new course!')
            ->view('course::emails.enrollment-confirmation');
    }
}
