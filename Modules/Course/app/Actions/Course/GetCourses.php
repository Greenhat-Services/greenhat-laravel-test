<?php

namespace Modules\Course\Actions\Course;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseTransformer;
use Spatie\Fractal\Fractal;

class GetCourses
{
    use AsAction;

    public function handle(array $filters = []): LengthAwarePaginator|Collection
    {
        $query = Course::query()
            ->withCount('enrollments');

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by difficulty level
        if (isset($filters['difficulty_level'])) {
            $query->where('difficulty_level', $filters['difficulty_level']);
        }

        // Filter by category
        if (isset($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('course_categories.id', $filters['category_id']);
            });
        }

        // Filter by instructor
        if (isset($filters['instructor'])) {
            $query->where('instructor', 'like', '%' . $filters['instructor'] . '%');
        }

        // Search by title or description
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Filter available for enrollment
        if (isset($filters['available_for_enrollment']) && $filters['available_for_enrollment']) {
            $query->availableForEnrollment();
        }

        // Include relationships
        if (isset($filters['include'])) {
            $includes = is_array($filters['include']) ? $filters['include'] : explode(',', $filters['include']);
            if (in_array('categories', $includes)) {
                $query->with('categories');
            }
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        if (isset($filters['per_page'])) {
            return $query->paginate($filters['per_page']);
        }

        return $query->get();
    }

    public function authorize(ActionRequest $request): bool
    {
        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request): LengthAwarePaginator|Collection
    {
        return $this->handle($request->all());
    }

    public function jsonResponse(LengthAwarePaginator|Collection $courses): Fractal
    {
        $fractal = fractal($courses, CourseTransformer::class);

        // Parse includes from request
        if (request()->has('include')) {
            $fractal->parseIncludes(request()->input('include'));
        }

        return $fractal;
    }
}
