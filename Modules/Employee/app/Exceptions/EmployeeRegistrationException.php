<?php

namespace Modules\Employee\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class EmployeeRegistrationException extends Exception
{
    protected int $statusCode;

    protected array $info;

    public function __construct(string $message, int $statusCode = 422, array $info = [], ?\Throwable $previous = null)
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
                'success' => false,
            ]
        ), $this->statusCode);
    }

    public static function invalidRegistrationCode(): static
    {
        return new static('Invalid registration code provided. Please use the correct registration code.', 400);
    }

    public static function duplicateEmail(string $email): static
    {
        return new static('An account with this email address already exists.', 422, [
            'email' => $email,
        ]);
    }

    public static function userCreationFailed(string $reason = ''): static
    {
        $message = 'Failed to create user account.';
        if ($reason) {
            $message .= ' Reason: ' . $reason;
        }

        return new static($message, 500);
    }

    public static function employeeCreationFailed(string $reason = ''): static
    {
        $message = 'Failed to create employee profile.';
        if ($reason) {
            $message .= ' Reason: ' . $reason;
        }

        return new static($message, 500);
    }
}
