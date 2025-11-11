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
        if ($model->confirmationEmailSentAt === null) {
            Log::info('Dispatching registration confirmation email job', [
                'employee_id' => $model->id,
            ]);
            SendEmployeeRegistrationConfirmationEmail::dispatch($model);
        }
    }

    public function saved(Employee $model): void
    {
        if ($model->user?->isNotA('employee')) {
            AssignRoleToUser::make()->handle($model->user, 'employee');
        }
    }
}
