<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\Course;

class DeleteCourse
{
    use AsAction;

    public function handle(Course $course): void
    {
        $course->categories()->detach();
        $course->delete();
    }
}
