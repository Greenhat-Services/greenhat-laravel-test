<?php

namespace Modules\Course\Data\Course;

use Modules\Base\Traits\LaravelDataHelper;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class EnrollEmployeeData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        #[Required, IntegerType, Exists('courses', 'id')]
        public int $courseId,
        #[Required, IntegerType, Exists('employees', 'id')]
        public int $employeeId,
        #[Max(1000)]
        public string|null|Optional $notes,
    ) {}
}
