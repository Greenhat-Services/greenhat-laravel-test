<?php

namespace Modules\Employee\Data\Enrollment;

use Modules\Base\Traits\LaravelDataHelper;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Modules\Employee\Models\Employee;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Exists;

class CreateEmployeeCourseEnrollmentData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        public Employee $employee,
        public int $courseId,
    ) {}

    public static function rules(): array
    {
        return [
            'courseId' => ['required', 'integer', 'exists:courses,id'],

        ];
    }
}
