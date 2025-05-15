<?php

namespace Modules\Course\Actions\Course;

use Exception;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseTransformer;
use Spatie\Fractal\Fractal;

class GetCourse
{
    use AsAction;

    public function asController(ActionRequest $request): Course
    {
        $course = Course::find($request->route('courseId'));

        if (! $course) {
            throw new CourseException('Course not found', 404);
        }

        return $course;
    }

    public function jsonResponse(Course $course): Fractal
    {
        return fractal($course, CourseTransformer::class);
    }
}
