<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Course\UpdateCourseData;
use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseTransformer;
use Spatie\Fractal\Fractal;

class UpdateCourse
{
    use AsAction;

    public function handle(UpdateCourseData $data): Course
    {
        $categoryIds = $data->has('categoryIds') ? $data->categoryIds : null;

        $data->course->update(
            $data->except('course', 'categoryIds')->toArray()
        );

        // Sync categories if provided
        if ($categoryIds !== null) {
            $data->course->categories()->sync($categoryIds);
        }

        $data->course->refresh();
        $data->course->load('categories');

        return $data->course;
    }

    public function authorize(ActionRequest $request): bool
    {
        $course = Course::findOrFail($request->route('id'));
        $request->merge(['course' => $course]);

        // Public access for testing
        return true;
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
        return fractal($course, CourseTransformer::class)
            ->parseIncludes('categories')
            ->addMeta(['message' => 'Course updated successfully']);
    }
}
