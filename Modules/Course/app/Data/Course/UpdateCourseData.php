<?php

namespace Modules\Course\Data\Course;

use Modules\Base\Traits\LaravelDataHelper;
use Modules\Course\Models\Course;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateCourseData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        public Course $course,
        #[Min(2), Max(255)]
        public string|Optional $title,
        public string|null|Optional $slug,
        #[Max(10000)]
        public string|null|Optional $description,
        #[Max(255)]
        public string|null|Optional $instructor,
        #[IntegerType, Min(1)]
        public int|null|Optional $durationHours,
        #[In(['beginner', 'intermediate', 'advanced'])]
        public string|null|Optional $difficultyLevel,
        #[In(['draft', 'published', 'archived'])]
        public string|Optional $status,
        #[IntegerType, Min(1)]
        public int|null|Optional $maxEnrollments,
        #[Date]
        public string|null|Optional $startDate,
        #[Date, After('start_date')]
        public string|null|Optional $endDate,
        #[ArrayType]
        public array|null|Optional $categoryIds,
    ) {}
}
