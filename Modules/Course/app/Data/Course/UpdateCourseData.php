<?php

namespace Modules\Course\Data\Course;

use Illuminate\Support\Optional;
use Modules\Base\Traits\LaravelDataHelper;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Modules\Course\Models\Course;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Exists;

class UpdateCourseData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        public Course $course,

        public string|Optional $title,

        public string|Optional|null $description,

        public string|Optional $status,

        #[ArrayType, Exists('categories', 'id')]
        public array|Optional $category_ids,
    ) {}

    public static function rules(): array
    {
        return [
            'status' => ['in:' . implode(',', array_keys(Course::getStatusOptions()))],
        ];
    }
}
