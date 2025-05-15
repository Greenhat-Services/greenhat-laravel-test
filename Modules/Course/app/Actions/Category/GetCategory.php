<?php

namespace Modules\Course\Actions\Category;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Category;

class GetCategory
{
    use AsAction;

    public function asController(ActionRequest $request): Category
    {
        $category = Category::find($request->route('categoryId'));

        if (! $category) {
            throw new CourseException('Category not found', 404);
        }

        return $category;
    }
}
