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
        $course = Course::create($data->except('categoryIds')->toArray());
        $course->categories()->sync($data->categoryIds);

        return $course->load('categories');
    }

    public function asController(ActionRequest $request): Course
    {
        $data = CreateCourseData::validateAndCreate($request->all());

        return $this->handle($data);
    }

    public function jsonResponse(Course $course): Fractal
    {
        return fractal($course, CourseTransformer::class)
            ->parseIncludes('categories');
    }
}
