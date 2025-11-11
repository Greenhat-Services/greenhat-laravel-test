<?php

namespace Modules\Course\Exceptions;

use Exception;

class CourseEnrollmentException extends Exception
{
    public function __construct(string $message = 'Course enrollment error', int $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function render()
    {
        return response()->json([
            'error' => $this->getMessage(),
        ], $this->getCode());
    }
}
