<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Course\CreateCourseData;
use Modules\Course\Models\Course;

class CreateCourse
{
    use AsAction;

    public function handle(CreateCourseData $data): Course
    {
        $course = Course::create($data->except('category_ids')->toArray());
        $course->categories()->sync($data->categoryIds);

        return $course->load('categories');
    }
}
