<?php

namespace Modules\Employee\Data;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Rule;

class EmployeeRegistrationData extends Data
{
    public function __construct(
        #[Rule('required|string|max:255')]
        public string $first_name,

        #[Rule('required|string|max:255')]
        public string $last_name,

        #[Rule('required|email|unique:users,email')]
        public string $email,

        #[Rule('required|string')]
        public string $role,

        #[Rule('required|string')]
        public string $highest_qualification,

        #[Rule('required|integer|min:0')]
        public int $desired_salary,

        #[Rule('nullable|string')]
        public ?string $note,

        #[Rule('required|string|in:ACME')] // Validates the special codew
        public string $special_code,
    ) {}
}