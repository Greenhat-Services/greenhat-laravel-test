<?php

namespace Modules\Course\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Modules\Course\Models\Course;

class CourseTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'categories',
        'enrollments',
    ];

    public function transform($data): array
    {
        $model = $data instanceof Course ? $data : new Course;

        return [
            'id' => $model->getKey(),
            'title' => $model->title,
            'slug' => $model->slug,
            'description' => $model->description,
            'instructor' => $model->instructor,
            'durationHours' => $model->durationHours,
            'difficultyLevel' => $model->difficultyLevel,
            'status' => $model->status,
            'maxEnrollments' => $model->maxEnrollments,
            'availableSlots' => $model->available_slots,
            'startDate' => $model->startDate?->toDateString(),
            'endDate' => $model->endDate?->toDateString(),
            'enrollmentsCount' => $model->enrollments_count ?? $model->enrollments()->count(),
            'isEnrollmentOpen' => $model->isEnrollmentOpen(),
            'createdAt' => $model->createdAt?->toIso8601String(),
            'updatedAt' => $model->updatedAt?->toIso8601String(),
        ];
    }

    public function includeCategories($data): Collection
    {
        $model = $data instanceof Course ? $data : new Course;

        return $this->collection($model->categories, new CourseCategoryTransformer);
    }

    public function includeEnrollments($data): Collection
    {
        $model = $data instanceof Course ? $data : new Course;

        return $this->collection($model->enrollments, new CourseEnrollmentTransformer);
    }
}
