<?php

namespace Modules\Employee\Observers;

use Illuminate\Support\Facades\Log;
use Modules\Employee\Jobs\SendEmployeeRegistrationConfirmationEmail;
use Modules\Employee\Models\Employee;
use Modules\User\Actions\User\AssignRoleToUser;

class EmployeeObserver
{
    public function created(Employee $model): void
    {
        Log::info('EmployeeObserver created event fired', [
            'employee_id' => $model->id,
            'confirmation_email_sent_at' => $model->confirmationEmailSentAt,
        ]);

        // Dispatch job to send registration confirmation email
        // Only if confirmation_email_sent_at is null (new registration)
        // Note: When employee is created via CreateEmployeeRegistration action,
        // the email is dispatched manually with the password, so this won't fire
        if ($model->confirmationEmailSentAt === null) {
            Log::info('Dispatching registration confirmation email job (without password)', [
                'employee_id' => $model->id,
            ]);
            // Dispatch without password (fallback for admin-created employees)
            SendEmployeeRegistrationConfirmationEmail::dispatch($model, null);
        }
    }

    public function saved(Employee $model): void
    {
        if ($model->user?->isNotA('employee')) {
            AssignRoleToUser::make()->handle($model->user, 'employee');
        }
    }
}
