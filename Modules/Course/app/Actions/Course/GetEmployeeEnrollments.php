<?php

namespace Modules\Course\Actions\Course;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\CourseEnrollment;
use Modules\Course\Transformers\CourseEnrollmentTransformer;
use Spatie\Fractal\Fractal;

class GetEmployeeEnrollments
{
    use AsAction;

    public function handle(array $filters = []): LengthAwarePaginator|Collection
    {
        $query = CourseEnrollment::query()->with(['course', 'employee']);

        // Filter by employee
        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        // Filter by course
        if (isset($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'enrolled_at';
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

    public function jsonResponse(LengthAwarePaginator|Collection $enrollments): Fractal
    {
        $fractal = fractal($enrollments, CourseEnrollmentTransformer::class);

        // Parse includes from request
        if (request()->has('include')) {
            $fractal->parseIncludes(request()->input('include'));
        }

        return $fractal;
    }
}
