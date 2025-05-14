<?php

namespace Modules\Course\Actions\Category;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Category;
use Modules\Course\Models\Course;

class DeleteCategory
{
    use AsAction;

    public function handle(int $id): bool
    {
        $category = Category::find($id);
        if (! $category) {
            throw new CourseException("category not found", 404);
        }

        $category->courses()->detach();
        return $category->delete();
    }
    public function asController(int $id): bool
    {
        return $this->handle($id);
    }
    public function jsonResponse(bool $deleted): \Illuminate\Http\JsonResponse
    {

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Category deleted successfully.' : 'Failed to delete category.'
        ]);
    }
}
