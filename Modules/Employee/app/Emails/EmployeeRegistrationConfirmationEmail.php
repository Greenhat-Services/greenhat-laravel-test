<?php

namespace Modules\Employee\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Employee\Models\Employee;

class EmployeeRegistrationConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Employee $employee,
    ) {}

    public function build(): self
    {
        return $this
            ->to($this->employee->user->email, $this->employee->user->name)
            ->view('employee::emails.registration-confirmation')
            ->subject('Welcome to Greenhat - Registration Confirmed')
            ->with([
                'employeeName' => $this->employee->user->name,
                'employeeEmail' => $this->employee->user->email,
                'employeeRole' => $this->employee->role,
                'registrationDate' => $this->employee->createdAt?->toDateTimeString(),
            ]);
    }
}
