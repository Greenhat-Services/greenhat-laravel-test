<?php

namespace Modules\Course\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Modules\Course\Models\Course;

class CourseTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'categories',
    ];

    public function transform(Course $course): array
    {
        return [
            'id' => $course->getKey(),
            'title' => $course->title,
            'description' => $course->description,
            'status' => $course->status,
            'createdAt' => $course->createdAt,
            'updatedAt' => $course->updatedAt,
        ];
    }

    public function includeCategories(Course $course): Collection
    {
        return $this->collection($course->categories, new CategoryTransformer());
    }
}

