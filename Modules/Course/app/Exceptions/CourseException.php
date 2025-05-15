<?php

namespace Modules\Course\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Throwable;
use Exception;

class CourseException extends Exception

    {
        protected int $statusCode;

        protected array $info;

        public function __construct(string $message, int $statusCode = 422, array $info = [], ?Throwable $previous = null)
        {
            parent::__construct($message, $statusCode, $previous);

            $this->statusCode = $statusCode;

            $this->info = $info;
        }

        public function render(): JsonResponse
        {
            return response()->json(array_merge(
                $this->info,
                [
                    'message' => $this->message,
                ]
            ), $this->statusCode);
        }
    }
// }
