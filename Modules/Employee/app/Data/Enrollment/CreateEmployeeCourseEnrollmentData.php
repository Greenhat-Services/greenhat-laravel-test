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
        public array $courseIds,
    ) {}

    public static function rules(): array
    {
        return [
            'courseIds' => ['required', 'array'],
            'courseIds.*' => ['required', 'integer', 'exists:courses,id'],

        ];
    }
    public static function messages(): array
    {
        return [
            'courseIds.required' => 'You must select at least one course.',
            'courseIds.array' => 'The courses field must be an array.',
            'courseIds.*.integer' => 'Each course ID must be an integer.',
            'courseIds.*.exists' => 'One or more selected courses do not exist.',
        ];
    }
}
