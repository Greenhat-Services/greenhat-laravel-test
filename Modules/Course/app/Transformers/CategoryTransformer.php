<?php

namespace Modules\Course\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Course\Models\Category;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(Category $category): array
    {
        return [
            'id' => $category->getKey(),
            'name' => $category->name,
            'createdAt' => $category->createdAt,
            'updatedAt' => $category->updatedAt,
        ];
    }
}
