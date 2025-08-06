<?php

namespace Modules\Employee\Actions\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Employee\Emails\RegistrationConfirmationEmail;
use Modules\Employee\Models\Employee;

class SendConfirmationEmail implements ShouldQueue
{
    use AsAction, Queueable;

    public function handle(Employee $employee, string $password): void
    {
        // 1. Send the email
        Mail::send(new RegistrationConfirmationEmail($employee, $password));

        // 2. Update the timestamp
        $employee->update(['confirmation_email_sent_at' => now()]);
    }
}