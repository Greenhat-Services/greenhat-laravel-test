<?php

namespace Modules\Course\Actions\CourseCategory;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\CourseCategory;
use Modules\Course\Transformers\CourseCategoryTransformer;
use Spatie\Fractal\Fractal;

class GetCourseCategoryById
{
    use AsAction;

    public function handle(int $id, array $includes = []): CourseCategory
    {
        $query = CourseCategory::query();

        if (in_array('courses', $includes)) {
            $query->with('courses');
        }

        return $query->findOrFail($id);
    }

    public function authorize(ActionRequest $request): bool
    {
        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request): CourseCategory
    {
        $includes = $request->has('include') ? explode(',', $request->input('include')) : [];

        return $this->handle($request->route('id'), $includes);
    }

    public function jsonResponse(CourseCategory $category): Fractal
    {
        $fractal = fractal($category, CourseCategoryTransformer::class);

        // Parse includes from request
        if (request()->has('include')) {
            $fractal->parseIncludes(request()->input('include'));
        }

        return $fractal;
    }
}
