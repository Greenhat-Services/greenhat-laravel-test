<?php

namespace Modules\Course\Data\Category;

use Modules\Base\Traits\LaravelDataHelper;
use Spatie\LaravelData\Data;

class CreateCategoryData extends Data
{
    use LaravelDataHelper;

    public function __construct(
        public string $name,
    ) {}
}
