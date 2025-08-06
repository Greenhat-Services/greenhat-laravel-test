<?php

namespace Modules\Employee\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Employee\Data\EmployeeRegistrationData;
use Modules\Employee\Models\Employee;
use Modules\Employee\Transformers\EmployeeTransformer;
use Modules\User\Models\User;
use Spatie\Fractal\Fractal;


class RegisterEmployeeAction
{
    use AsAction;

    /**
     * Handles creating the user and employee records.
     * @param EmployeeRegistrationData $data The validated data from the API request.
     * @return Employee The newly created employee record.
     */

     public function handle(EmployeeRegistrationData $data): Employee
    { // Database transaction to ensure data consistency
        return DB::transaction(function () use ($data) {
            // 1. Auto-generate a secure password for the new user.
            $password = Str::random(16);
            //2. Create the User record. 
            $user = User::create([
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'email' => $data->email,
                'password' => Hash::make($password),
            ]);
            //3. Create the corresponding Employee record.
            $employee = Employee::create([
                'user_id' => $user->id,
                'role' => $data->role,
                'highest_qualification' => $data->highest_qualification,
                'desired_salary' => $data->desired_salary,
                'note' => $data->note,
            ]);

            // My apporach is to temporarily attach the plain-text password to the employee model.
            // This allows the EmployeeObserver to access it to send the confirmation email.

            $employee->plain_text_password = $password;

            return $employee;
        });
    }
    /**
     * This method allows the Action to be used directly in a route definition.
     * It validates the request and calls the handle method.
     */

    public function asController(ActionRequest $request): Employee
    {
        // Create the DTO from the request. This automatically triggers validation.
        $data = EmployeeRegistrationData::from($request);
        return $this->handle($data);

    }

    /**
     * This method transforms the employee data into a JSON response.
     * It uses the Fractal library to format the data.
     */

    public function jsonResponse(Employee $employee): Fractal
    {
        return fractal($employee, new EmployeeTransformer())
        ->parseIncludes('user') // Include the user details in the response
        ->withResourceName('employee');

    }

}