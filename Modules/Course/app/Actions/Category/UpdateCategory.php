<?php

namespace Modules\Course\Actions\Category;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Category\UpdateCategoryData;
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

    public function jsonResponse(Category $category): Fractal
    {
        return fractal($category, CategoryTransformer::class);
    }
}
