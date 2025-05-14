<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Course\UpdateCourseData;
use Modules\Course\Models\Course;

class UpdateCourse
{
    use AsAction;

    public function handle(UpdateCourseData $data): Course
    {
        $course = $data->course;
        $course->update($data->except('course', 'category_ids')->toArray());

        if (!empty($data->category_ids)) {
            $course->categories()->sync($data->categoryIds);
        }

        return $course->load('categories');
    }
}
