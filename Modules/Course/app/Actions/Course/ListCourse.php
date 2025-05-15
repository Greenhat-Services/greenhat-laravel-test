<?php

namespace Modules\Course\Actions\Course;

use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseTransformer;
use Spatie\Fractal\Fractal;

class ListCourse
{
    use AsAction;

    public function handle(ActionRequest $request): Collection
    {
        $courses = Course::when($request->filled('search'), fn($query) => $query->search($request->search))
            ->when($request->filled('status'), fn($query) => $query->FilterByStatus($request->status))
            ->when($request->filled('category'), fn($query) => $query->FilterByCategory($request->category))
            ->get();
        return $courses;
    }

    public function jsonResponse(Collection $courses): Fractal
    {
        return fractal()
            ->collection($courses, new CourseTransformer());
    }
}
