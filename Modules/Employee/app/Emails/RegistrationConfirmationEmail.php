<?php

namespace Modules\Employee\Emails;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Modules\Employee\Models\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;


/**
 * Implements Queue
 *  This class defines the email, its data, and tells Laravel to send it in the background
 * Keeps Api response time low.
 */

class RegistrationConfirmationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    

    public function __construct(
        public Employee $employee,
        public string $password, // Plain-text password
    ) {}

    public function build(): self
    {
        return $this
            ->to($this->employee->user->email, $this->employee->user->name)
            ->subject('Welcome to Greenhat - Your Account is Ready') 
            ->view('employee::emails.registration-confirmation'); // This is the email template.
    }


}