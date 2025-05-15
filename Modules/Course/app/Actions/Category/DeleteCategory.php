<?php

namespace Modules\Course\Actions\Category;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Category;
use Modules\Course\Models\Course;

class DeleteCategory
{
    use AsAction;

    public function handle(Category $category): bool
    {
        $category->courses()->detach();
        return $category->delete();
    }

    public function authorize(ActionRequest $request): bool
    {
        $category = Category::find($request->route('categoryId'));

        if (! $category) {
            throw new CourseException('Category not found', 404);
        }

        $request->merge(['category' => $category]);

        $isAuthorized = $request->user()->isA('admin');
        if (! $isAuthorized) {
            throw new CourseException('Unauthorized', 403);
        }

        return $isAuthorized;
    }

    public function asController(ActionRequest $request): bool
    {
        $deleted = $this->handle($request->category);

        return $deleted;
    }
    public function jsonResponse(bool $deleted): \Illuminate\Http\JsonResponse
    {

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Category deleted successfully.' : 'Failed to delete category.'
        ]);
    }
}
