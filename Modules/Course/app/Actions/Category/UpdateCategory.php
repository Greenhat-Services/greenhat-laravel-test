<?php

namespace Modules\Course\Actions\Category;

use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Category\UpdateCategoryData;
use Modules\Course\Exceptions\CourseException;
use Modules\Course\Models\Category;
use Modules\Course\Transformers\CategoryTransformer;
use Spatie\Fractal\Fractal;

class UpdateCategory
{
    use AsAction;

    public function handle(UpdateCategoryData $data): Category
    {
        $category = $data->category;
        $category->update($data->only('name')->toArray());

        return $category;
    }

    public function authorize(ActionRequest $request): bool
    {
        $category = Category::find($request->route('categoryId'));

        if (! $category) {
            throw new CourseException('Category not found', 404);
        }
        $request->merge(['category' => $category]);
        return $request->user()->isA('admin');
    }

    public function asController(ActionRequest $request): Category
    {
        $category = $request->category;

        $data = UpdateCategoryData::validateAndCreate([
            ...$request->all(),
            'category' => $category,
        ]);

        return $this->handle($data);
    }

    public function jsonResponse(Category $category): Fractal
    {
        return fractal($category, CategoryTransformer::class);
    }
}
