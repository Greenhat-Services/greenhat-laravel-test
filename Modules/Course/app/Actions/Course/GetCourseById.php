<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseTransformer;
use Spatie\Fractal\Fractal;

class GetCourseById
{
    use AsAction;

    public function handle(int $id, array $includes = []): Course
    {
        $query = Course::query();

        if (in_array('categories', $includes)) {
            $query->with('categories');
        }

        if (in_array('enrollments', $includes)) {
            $query->with('enrollments');
        }

        return $query->findOrFail($id);
    }

    public function authorize(ActionRequest $request): bool
    {
        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request): Course
    {
        $includes = $request->has('include') ? explode(',', $request->input('include')) : [];

        return $this->handle($request->route('id'), $includes);
    }

    public function jsonResponse(Course $course): Fractal
    {
        $fractal = fractal($course, CourseTransformer::class);

        // Parse includes from request
        if (request()->has('include')) {
            $fractal->parseIncludes(request()->input('include'));
        }

        return $fractal;
    }
}
