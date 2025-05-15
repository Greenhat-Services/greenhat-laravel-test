<?php

namespace Modules\Course\Actions\Course;

use Exception;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Course;

class GetCourse
{
    use AsAction;

    public function handle(int $id): Course
    {
        $course = Course::with('categories')->find($id);
        if (! $course) {
            throw new CourseException("course not found", 404);
        }

        return $course;
    }
    public function asController(ActionRequest $request): Course
    {
        $course = Course::find($request->route('courseId'));

        if (! $course) {
            throw new CourseException('Course not found', 404);
        }

        return $course;
    }
}
