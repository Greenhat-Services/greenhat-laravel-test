<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Exceptions\CourseEnrollmentException;
use Modules\Course\Models\Course;
use Spatie\Fractal\Fractal;

class DeleteCourse
{
    use AsAction;

    public function handle(Course $course): bool
    {
        // Check for active enrollments
        $activeEnrollments = $course->enrollments()
            ->whereIn('status', ['enrolled', 'in_progress'])
            ->count();

        if ($activeEnrollments > 0) {
            throw new CourseEnrollmentException(
                'Cannot delete course with active enrollments',
                422
            );
        }

        return $course->delete();
    }

    public function authorize(ActionRequest $request): bool
    {
        $course = Course::findOrFail($request->route('id'));
        $request->merge(['course' => $course]);

        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request)
    {
        $course = $request->course;
        $this->handle($course);

        return response()->noContent(); // 204 No Content
    }
}
