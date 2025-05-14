<?php

namespace Modules\Employee\Data\Employee;

use Modules\Base\Traits\LaravelDataHelper;
use Modules\Employee\Models\Employee;
use Spatie\LaravelData\Data;
use Modules\User\Data\User\CreateUserData;

class CreateEmployeeData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        public string $role,
        public ?string $highestQualification = null,
        public ?float $desiredSalary = null,
        public CreateUserData $userData,
    ) {}

    public static function rules(): array
    {
        return [
            'role' => ['in:' . implode(',', array_keys(Employee::getRoleOptions()))],
        ];
    }
}
