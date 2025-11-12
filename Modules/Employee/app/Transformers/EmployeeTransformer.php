<?php

namespace Modules\Employee\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Modules\Course\Transformers\CourseEnrollmentTransformer;
use Modules\Employee\Models\Employee;
use Modules\User\Transformers\UserTransformer;

class EmployeeTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'user',
        'enrollments',
    ];

    public function transform($data): array
    {
        $model = $data instanceof Employee ? $data : new Employee;

        return [
            'id' => $model->getKey(),
            'userId' => $model->userId,
            'role' => $model->role,
            'createdAt' => $model->createdAt,
            'updatedAt' => $model->updatedAt,
        ];
    }

    public function includeUser($data): Item
    {
        $model = $data instanceof Employee ? $data : new Employee;

        return $this->item($model->user, new UserTransformer);
    }

    public function includeEnrollments($data): Collection
    {
        $model = $data instanceof Employee ? $data : new Employee;

        return $this->collection($model->enrollments, new CourseEnrollmentTransformer);
    }
}
