<?php

namespace Modules\Employee\Actions\Enrollment;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Employee\Data\Enrollment\CreateEmployeeCourseEnrollmentData;
use Modules\Employee\Exceptions\EmployeeException;
use Modules\Employee\Models\Employee;
use Modules\Employee\Transformers\EmployeeTransformer;
use Spatie\Fractal\Fractal;

class CreateEmployeeCourseEnrollment
{
    use AsAction;

    public function handle(CreateEmployeeCourseEnrollmentData $data): Employee
    {
        $employee = $data->employee;
        $employee->courses()->syncWithoutDetaching($data->courseIds);

        return $employee->load('courses');
    }

    public function authorize(ActionRequest $request): bool
    {
        $employee = Employee::find($request->route('employeeId'));

        if(! $employee) {
            throw new EmployeeException('Employee not found', 404);
        }

        $request->merge(['employee' => $employee]);

        return $request->user()->can('updateProfile', $request->employee);
    }

    public function asController(ActionRequest $request): Employee
    {
        $data = CreateEmployeeCourseEnrollmentData::validateAndCreate($request->all());

        return $this->handle($data);
    }

    public function jsonResponse(Employee $employee): Fractal
    {
        return fractal($employee, EmployeeTransformer::class)
            ->parseIncludes('courses');
    }
}
