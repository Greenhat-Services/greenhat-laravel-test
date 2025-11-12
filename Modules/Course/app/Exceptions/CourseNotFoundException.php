<?php

namespace Modules\Course\Exceptions;

use Exception;

class CourseNotFoundException extends Exception
{
    public function __construct(string $message = 'Course not found', int $code = 404)
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
