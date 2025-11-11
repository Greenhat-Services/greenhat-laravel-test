<?php

namespace Modules\Course\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Modules\Course\Models\CourseCategory;

class CourseCategoryTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'courses',
    ];

    public function transform($data): array
    {
        $model = $data instanceof CourseCategory ? $data : new CourseCategory;

        return [
            'id' => $model->getKey(),
            'name' => $model->name,
            'slug' => $model->slug,
            'description' => $model->description,
            'isActive' => $model->isActive,
            'coursesCount' => $model->courses_count ?? $model->courses()->count(),
            'createdAt' => $model->createdAt?->toIso8601String(),
            'updatedAt' => $model->updatedAt?->toIso8601String(),
        ];
    }

    public function includeCourses($data): Collection
    {
        $model = $data instanceof CourseCategory ? $data : new CourseCategory;

        return $this->collection($model->courses, new CourseTransformer);
    }
}
