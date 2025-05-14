<?php

namespace Modules\Course\Actions\Category;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Category;
use Modules\Course\Models\Course;

class GetCategory
{
    use AsAction;

    public function handle(int $id): Category
    {
        $category = Category::find($id);
        if (! $category) {
            throw new CourseException("category not found", 404);
        }

        return $category;
    }
}
