<?php

namespace Modules\Employee\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Employee\Models\Employee;

class EmployeeConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Employee $employee,
    ) {}

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->to($this->employee->user->email, $this->employee->username)
            ->subject('Welcome to the Greenhat team!')
            ->view('employee::emails.welcome') // Use module view
            ->with(['employee' => $this->employee]);
    }
}
