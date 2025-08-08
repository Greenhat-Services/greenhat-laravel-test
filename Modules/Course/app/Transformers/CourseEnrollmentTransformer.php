<?php

namespace Modules\Course\Transformers;

use League\Fractal\TransformerAbstract;

class CourseEnrollmentTransformer extends TransformerAbstract
{
    public function transform($data): array
    {
        $enrolledNow = (bool) ($data['enrolled_now'] ?? false);

        return [
            'status'       => 'success',
            'message'      => $enrolledNow ? 'Successfully enrolled in course.' : 'Already enrolled.',
            'enrolled_now' => $enrolledNow,
            'course'       => $data['course'] ?? null,
            'employee'     => $data['employee'] ?? null,
        ];
    }
}
