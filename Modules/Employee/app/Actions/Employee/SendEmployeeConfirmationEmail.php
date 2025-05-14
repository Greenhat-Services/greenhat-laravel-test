<?php

namespace Modules\Employee\Actions\Employee;

use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Employee\Data\Employee\UpdateEmployeeData;
use Modules\Employee\Emails\EmployeeConfirmationEmail;
use Modules\Employee\Models\Employee;
use Spatie\LaravelData\Optional;

class SendEmployeeConfirmationEmail
{
    use AsAction;

    public function handle(Employee $employee): void
    {
        // Send the confirmation email
        Mail::send(new EmployeeConfirmationEmail($employee));

        // Update the confirmationEmailSentAt timestamp
        UpdateEmployee::make()->handle(new UpdateEmployeeData(
            employee: $employee,
            userData: Optional::create(),
            confirmationEmailSentAt: now(),
        ));
    }
}
