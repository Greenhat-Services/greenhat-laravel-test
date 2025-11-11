<?php

namespace Modules\Employee\Transformers;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Modules\Employee\Models\Employee;
use Modules\User\Transformers\UserTransformer;

class EmployeeRegistrationTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
        'user',
    ];

    public function transform($data): array
    {
        $model = $data instanceof Employee ? $data : new Employee;

        return [
            'id' => $model->getKey(),
            'role' => $model->role,
            'highestQualification' => $model->highestQualification,
            'desiredSalary' => $model->desiredSalary,
            'note' => $model->note,
            'confirmationEmailSentAt' => $model->confirmationEmailSentAt,
            'createdAt' => $model->createdAt,
        ];
    }

    public function includeUser($data): Item
    {
        $model = $data instanceof Employee ? $data : new Employee;

        return $this->item($model->user, new UserTransformer);
    }
}
