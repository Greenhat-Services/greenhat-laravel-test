<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Course;

class DeleteCourse
{
    use AsAction;

    public function handle(Course $course): bool
    {
        $course->categories()->detach();
        return $course->delete();
    }

    public function authorize(ActionRequest $request): bool
    {
        $course = Course::find($request->route('courseId'));

        if (! $course) {
            throw new CourseException('Category not found', 404);
        }
        $request->merge(['course' => $course]);

        return $request->user()->isA('admin');
    }

    public function asController(ActionRequest $request): \Illuminate\Http\JsonResponse
    {
        $deleted = $this->handle($request->course);

        return $this->jsonResponse($deleted);
    }
    public function jsonResponse(bool $deleted): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Course deleted successfully.' : 'Failed to delete course.'
        ]);
    }
}
