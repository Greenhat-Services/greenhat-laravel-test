<?php

namespace Modules\Employee\Data\Employee;

use Modules\Base\Traits\LaravelDataHelper;
use Modules\User\Models\User;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CreateEmployeeRegistrationData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        #[Required, In(['ACME'])]
        public string $registrationCode,
        #[Required, Min(2), Max(255)]
        public string $firstName,
        #[Required, Min(2), Max(255)]
        public string $lastName,
        #[Required, Email, Unique(User::class, 'email')]
        public string $email,
        #[Required, Max(255)]
        public string $role,
        #[Required, Max(255)]
        public string $highestQualification,
        #[Numeric, Min(0)]
        public float|null|Optional $desiredSalary,
        #[Max(5000)]
        public string|null|Optional $note,
    ) {}
}
