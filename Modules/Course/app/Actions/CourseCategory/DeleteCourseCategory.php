<?php

namespace Modules\Course\Actions\CourseCategory;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\CourseCategory;
use Spatie\Fractal\Fractal;

class DeleteCourseCategory
{
    use AsAction;

    public function handle(CourseCategory $category): bool
    {
        return $category->delete();
    }

    public function authorize(ActionRequest $request): bool
    {
        $category = CourseCategory::findOrFail($request->route('id'));
        $request->merge(['category' => $category]);

        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request)
    {
        $category = $request->category;
        $this->handle($category);

        return response()->noContent(); // 204 No Content
    }
}
