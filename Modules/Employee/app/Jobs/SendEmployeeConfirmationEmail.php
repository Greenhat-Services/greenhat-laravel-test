<?php

namespace Modules\Employee\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Modules\Employee\Actions\Employee\UpdateEmployee;
use Modules\Employee\Data\Employee\UpdateEmployeeData;
use Modules\Employee\Emails\EmployeeConfirmationEmail;
use Modules\Employee\Models\Employee;
use Spatie\LaravelData\Optional;

class SendEmployeeConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Employee $employee) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::send(new EmployeeConfirmationEmail($this->employee));

        UpdateEmployee::make()->handle(new UpdateEmployeeData(
            employee: $this->employee,
            userData: Optional::create(), // no need to update user here
            confirmationEmailSentAt: now(),
        ));
    }
}
