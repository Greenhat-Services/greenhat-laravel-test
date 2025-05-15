<?php

namespace Modules\Employee\Observers;

use Modules\Employee\Jobs\SendEmployeeConfirmationEmailJob;
use Modules\Employee\Models\Employee;
use Modules\User\Actions\User\AssignRoleToUser;

class EmployeeObserver
{
    public function created(Employee $model): void
    {
        SendEmployeeConfirmationEmailJob::dispatch($model);
    }

    public function saved(Employee $model): void
    {
        if ($model->user?->isNotA('employee')) {
            AssignRoleToUser::make()->handle($model->user, 'employee');
        }
    }
}
