<?php

namespace Modules\Employee\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Employee\Emails\EmployeeRegistrationConfirmationEmail;
use Modules\Employee\Models\Employee;

class SendEmployeeRegistrationConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Employee $employee
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send the confirmation email
            Mail::send(new EmployeeRegistrationConfirmationEmail($this->employee));

            // Update the confirmation_email_sent_at timestamp
            $this->employee->update([
                'confirmationEmailSentAt' => now(),
            ]);

            Log::info('Registration confirmation email sent successfully', [
                'employee_id' => $this->employee->id,
                'email' => $this->employee->user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send registration confirmation email', [
                'employee_id' => $this->employee->id,
                'email' => $this->employee->user->email,
                'error' => $e->getMessage(),
            ]);

            // Re-throw the exception to trigger job retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Registration confirmation email job failed permanently', [
            'employee_id' => $this->employee->id,
            'email' => $this->employee->user->email,
            'error' => $exception->getMessage(),
        ]);
    }
}
