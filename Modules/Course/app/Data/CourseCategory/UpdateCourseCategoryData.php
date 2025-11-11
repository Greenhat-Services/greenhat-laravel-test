<?php

namespace Modules\Course\Data\CourseCategory;

use Modules\Base\Traits\LaravelDataHelper;
use Modules\Course\Models\CourseCategory;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateCourseCategoryData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        public CourseCategory $category,
        #[Min(2), Max(255)]
        public string|Optional $name,
        public string|null|Optional $slug,
        #[Max(5000)]
        public string|null|Optional $description,
        public bool|Optional $isActive,
    ) {}
}
