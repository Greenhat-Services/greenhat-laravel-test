<?php

namespace Modules\Course\Data\Course;

use Modules\Base\Traits\LaravelDataHelper;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Modules\Course\Models\Course;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\In;

class CreateCourseData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        public string $title,
        public string $description,
        #[Required]
        public string $status,
        #[Required, ArrayType, Exists('categories', 'id')]
        public array $categoryIds,
    ) {}

    public static function rules(): array
    {
        return [
            'status' => ['required','in:' . implode(',', array_keys(Course::getStatusOptions()))],
        ];
    }
}
