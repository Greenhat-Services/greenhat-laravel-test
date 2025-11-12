<?php

namespace Modules\Course\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Course\Models\CourseEnrollment;

class CourseEnrollmentConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public CourseEnrollment $enrollment;

    public function __construct(CourseEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function build()
    {
        return $this
            ->subject('Course Enrollment Confirmation - ' . $this->enrollment->course->title)
            ->view('course::emails.enrollment-confirmation')
            ->with([
                'enrollment' => $this->enrollment,
                'course' => $this->enrollment->course,
                'employee' => $this->enrollment->employee,
            ]);
    }
}
