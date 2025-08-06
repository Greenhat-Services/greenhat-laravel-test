<?php

namespace Modules\Employee\Observers;

use Modules\Employee\Models\Employee;
use Modules\User\Actions\User\AssignRoleToUser;
use Illuminate\Support\Facades\Mail;
use Modules\Employee\Emails\RegistrationConfirmationEmail;

class EmployeeObserver
{

    /**
     * Handles the employee "created" event
     * automatically called after the employee is created
     */ 

    public function created(Employee $employee): void
    {
        // check if the plain_text_password was set by the RegisterEmployeeAction
        // This ensures this logic only runs during inital registration
        if (isset($employee->plain_text_password)) {
            Mail::send(new RegistrationConfirmationEmail($employee, $employee->plain_text_password));
            // Update the timestamp and save without triggering observers again to prevent loops.
            $employee->forceFill([
                'confirmation_email_sent_at' => now(),
            ])->saveQuietly();
        }
    }

    /**
     * Handles the employee "saved" event,
     * This ensures the user is assigned the 'employee' role
     */ 

    public function saved(Employee $model): void
    {
        if ($model->user?->isNotA('employee')) {
            AssignRoleToUser::make()->handle($model->user, 'employee');
        }
    }
}