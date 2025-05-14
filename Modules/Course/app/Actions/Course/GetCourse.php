<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\Course;

class GetCourse
{
    use AsAction;

    public function handle(int $id): Course
    {
        return Course::with('categories')->findOrFail($id);
    }
}
