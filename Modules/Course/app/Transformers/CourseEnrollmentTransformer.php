<?php

namespace Modules\Course\Transformers;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Modules\Course\Models\CourseEnrollment;
use Modules\Employee\Transformers\EmployeeTransformer;

class CourseEnrollmentTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'course',
        'employee',
    ];

    public function transform($data): array
    {
        $model = $data instanceof CourseEnrollment ? $data : new CourseEnrollment;

        return [
            'id' => $model->getKey(),
            'courseId' => $model->courseId,
            'employeeId' => $model->employeeId,
            'status' => $model->status,
            'enrolledAt' => $model->enrolledAt?->toIso8601String(),
            'completedAt' => $model->completedAt?->toIso8601String(),
            'progressPercentage' => $model->progressPercentage,
            'notes' => $model->notes,
            'createdAt' => $model->createdAt?->toIso8601String(),
            'updatedAt' => $model->updatedAt?->toIso8601String(),
        ];
    }

    public function includeCourse($data): Item
    {
        $model = $data instanceof CourseEnrollment ? $data : new CourseEnrollment;

        return $this->item($model->course, new CourseTransformer);
    }

    public function includeEmployee($data): Item
    {
        $model = $data instanceof CourseEnrollment ? $data : new CourseEnrollment;

        return $this->item($model->employee, new EmployeeTransformer);
    }
}
