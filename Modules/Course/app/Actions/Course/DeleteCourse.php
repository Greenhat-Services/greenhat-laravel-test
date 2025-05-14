<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Course;

class DeleteCourse
{
    use AsAction;

    public function handle(int $id): bool
    {
        $course = Course::find($id);
        if (! $course) {
            throw new CourseException("course not found", 404);
        }

        $course->categories()->detach();
        return $course->delete();
    }
    public function asController(int $id): bool
    {
        return $this->handle($id);
    }
    public function jsonResponse(bool $deleted): \Illuminate\Http\JsonResponse
    {

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Course deleted successfully.' : 'Failed to delete course.'
        ]);
    }
}
