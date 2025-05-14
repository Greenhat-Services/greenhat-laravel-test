<?php

namespace Modules\Course\Actions\Category;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Category\CreateCategoryData;
use Modules\Course\Models\Category;
use Modules\Course\Transformers\CategoryTransformer;
use Spatie\Fractal\Fractal;

class CreateCategory
{
    use AsAction;

    public function handle(CreateCategoryData $data): Category
    {
        $category = Category::create($data->only('name')->toArray());

        return $category;
    }

    public function asController(ActionRequest $request): Category
    {
        $data = CreateCategoryData::validateAndCreate($request->all());

        return $this->handle($data);
    }

    public function jsonResponse(Category $category): Fractal
    {
        return fractal($category, CategoryTransformer::class);
    }
}
