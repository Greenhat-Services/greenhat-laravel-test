<?php

namespace Modules\Employee\Actions\Employee;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Employee\Data\Employee\CreateEmployeeRegistrationData;
use Modules\Employee\Models\Employee;
use Modules\Employee\Transformers\EmployeeRegistrationTransformer;
use Modules\User\Models\User;
use Spatie\Fractal\Fractal;

class CreateEmployeeRegistration
{
    use AsAction;

    public function handle(CreateEmployeeRegistrationData $data): Employee
    {
        return DB::transaction(function () use ($data) {
            // Auto-generate a secure password
            $generatedPassword = Str::random(16);

            // Create User record
            $user = User::create([
                'firstName' => $data->firstName,
                'lastName' => $data->lastName,
                'name' => $data->firstName . ' ' . $data->lastName,
                'email' => $data->email,
                'password' => Hash::make($generatedPassword),
            ]);

            // Create Employee record
            $employee = Employee::create([
                'userId' => $user->id,
                'role' => $data->role,
                'highestQualification' => $data->highestQualification,
                'desiredSalary' => $data->desiredSalary,
                'note' => $data->note,
            ]);

            // Load the user relationship
            $employee->load('user');

            return $employee;
        });
    }

    public function authorize(ActionRequest $request): bool
    {
        // Public endpoint, no authorization needed
        return true;
    }

    public function asController(ActionRequest $request): Employee
    {
        $data = CreateEmployeeRegistrationData::validateAndCreate($request->all());

        return $this->handle($data);
    }

    public function jsonResponse(Employee $employee): Fractal
    {
        return fractal($employee, EmployeeRegistrationTransformer::class)
            ->parseIncludes('user')
            ->addMeta([
                'success' => true,
                'message' => 'Registration successful! A confirmation email has been sent to your email address.',
            ]);
    }
}
