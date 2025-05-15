<?php

namespace Modules\Employee\Actions\Employee;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Employee\Data\Employee\CreateEmployeeData;
use Modules\Employee\Models\Employee;
use Modules\Employee\Transformers\EmployeeTransformer;
use Modules\User\Actions\User\CreateUser;
use Spatie\Fractal\Fractal;

class CreateEmployee
{
    use AsAction;

    public function handle(CreateEmployeeData $data): Employee
    {
        // Create the user first
        $user = CreateUser::make()->handle($data->userData);

        // Create the employee and associate the user
        $employee = $user->employee()->create(
            $data->only('role', 'lastName', 'highestQualification', 'desiredSalary')
                ->toArray()
        );

        return $employee;
    }

    public function authorize(ActionRequest $request): bool
    {

        return $request->user()->isA('admin');
    }

    public function asController(ActionRequest $request): Employee
    {
        $data = CreateEmployeeData::validateAndCreate($request->all());

        return $this->handle($data);
    }

    public function jsonResponse(Employee $employee): Fractal
    {
        return fractal($employee, EmployeeTransformer::class)
            ->parseIncludes('user');
    }
}
