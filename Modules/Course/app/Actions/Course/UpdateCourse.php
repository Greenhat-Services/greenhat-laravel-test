<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Course\UpdateCourseData;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseTransformer;
use Spatie\Fractal\Fractal;

class UpdateCourse
{
    use AsAction;

    public function handle(UpdateCourseData $data): Course
    {
        $course = $data->course;
        $course->update($data->except('course')->toArray());

        return $course;
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

    public function asController(ActionRequest $request): Course
    {
        $course = $request->course;
        $data = UpdateCourseData::validateAndCreate([
            ...$request->all(),
            'course' => $course,
        ]);

        return $this->handle($data);
    }

    public function jsonResponse(Course $course): Fractal
    {
        return fractal($course, CourseTransformer::class);
    }
}
