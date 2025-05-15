<?php

namespace Modules\Employee\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Employee\Actions\Employee\SendEmployeeConfirmationEmail;
use Modules\Employee\Models\Employee;

class SendEmployeeConfirmationEmailJob implements ShouldQueue
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
        SendEmployeeConfirmationEmail::run($this->employee);
    }
}
