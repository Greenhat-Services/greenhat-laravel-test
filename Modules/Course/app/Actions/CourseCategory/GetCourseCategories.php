<?php

namespace Modules\Course\Actions\CourseCategory;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\CourseCategory;
use Modules\Course\Transformers\CourseCategoryTransformer;
use Spatie\Fractal\Fractal;

class GetCourseCategories
{
    use AsAction;

    public function handle(array $filters = []): LengthAwarePaginator|Collection
    {
        $query = CourseCategory::query()
            ->withCount('courses');

        // Filter by active status
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        // Search by name
        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'name';
        $sortOrder = $filters['sort_order'] ?? 'asc';
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

    public function jsonResponse(LengthAwarePaginator|Collection $categories): Fractal
    {
        return fractal($categories, CourseCategoryTransformer::class);
    }
}
