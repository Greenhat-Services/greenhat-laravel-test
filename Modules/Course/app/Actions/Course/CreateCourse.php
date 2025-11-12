<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Course\CreateCourseData;
use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseTransformer;
use Spatie\Fractal\Fractal;

class CreateCourse
{
    use AsAction;

    public function handle(CreateCourseData $data): Course
    {
        $categoryIds = $data->categoryIds ?? [];

        $course = Course::create($data->except('categoryIds')->toArray());

        // Sync categories if provided
        if (!empty($categoryIds)) {
            $course->categories()->sync($categoryIds);
        }

        $course->load('categories');

        return $course;
    }

    public function authorize(ActionRequest $request): bool
    {
        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request): Course
    {
        $data = CreateCourseData::validateAndCreate($request->all());

        return $this->handle($data);
    }

    public function jsonResponse(Course $course): Fractal
    {
        return fractal($course, CourseTransformer::class)
            ->parseIncludes('categories')
            ->addMeta(['message' => 'Course created successfully']);
    }
}
