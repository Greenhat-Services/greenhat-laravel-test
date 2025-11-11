<?php

namespace Modules\Employee\Data;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule as ValidationRule;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class EmployeeRegistrationData extends Data
{

    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public string $role,
        public string $highest_qualification,
        public int $desired_salary,
        public ?string $note,
        public string $special_code,
    ) {}

    /**
     * This class defines the data structure for employee registration. Uses Spatie Laravel Data to automatically validate and transform the data
     * spatie rules method is used to define the validation rules for the data.
     * Moved the special code to config file.
     * using this config file, we can easily change the special code without changing the code or hardcoding it.
     */

     public static function rules(ValidationContext $context): array
     {
         return [
             'first_name' => ['required', 'string', 'max:255'],
             'last_name' => ['required', 'string', 'max:255'],
             'email' => ['required', 'email', 'unique:users,email'],
             'role' => ['required', 'string'],
             'highest_qualification' => ['required', 'string'],
             'desired_salary' => ['required', 'integer', 'min:0'],
             'note' => ['nullable', 'string'],
             'special_code' => ['required', 'string', ValidationRule::in([config('employee.registration_code')])],
         ];
     }
}