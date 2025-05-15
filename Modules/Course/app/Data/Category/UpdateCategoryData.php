<?php

namespace Modules\Course\Data\Category;

use Illuminate\Support\Optional;
use Modules\Base\Traits\LaravelDataHelper;
use Modules\Course\Models\Category;
use Spatie\LaravelData\Data;

class UpdateCategoryData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        public Category $category,

        public string|Optional $name,
    ) {}
}
