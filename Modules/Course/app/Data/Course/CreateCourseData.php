<?php

namespace Modules\Course\Data\Course;

use Modules\Base\Traits\LaravelDataHelper;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Modules\Course\Models\Course;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Exists;

class CreateCourseData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        public string $title,
        public string $description,
        public ?string $status = "draft",
        #[Required, ArrayType, Exists('categories', 'id')]
        public array $categoryIds,
    ) {}

    public static function rules(): array
    {
        return [
            'status' => ['in:' . implode(',', array_keys(Course::getStatusOptions()))],
        ];
    }
}
