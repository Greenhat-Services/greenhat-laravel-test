<?php

namespace Modules\Course\Data\CourseCategory;

use Modules\Base\Traits\LaravelDataHelper;
use Modules\Course\Models\CourseCategory;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CreateCourseCategoryData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        #[Required, Min(2), Max(255)]
        public string $name,
        #[Unique(CourseCategory::class, 'slug')]
        public string|null|Optional $slug,
        #[Max(5000)]
        public string|null|Optional $description,
        public bool|Optional $isActive = true,
    ) {}
}
