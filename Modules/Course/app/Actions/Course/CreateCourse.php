<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Course\CreateCourseData;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseTransformer;
use Spatie\Fractal\Fractal;

class CreateCourse
{
    use AsAction;

    public function handle(CreateCourseData $data): Course
    {
        $course = Course::create($data->except('categoryIds')->toArray());

        return $course;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->isA('admin');
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
