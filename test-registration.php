<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Employee\Models\Employee;

echo "Checking registration status...\n\n";

$employees = Employee::with('user')->get();

foreach ($employees as $employee) {
    echo "Employee ID: {$employee->id}\n";
    echo "  Name: {$employee->user->name}\n";
    echo "  Email: {$employee->user->email}\n";
    echo "  Role: {$employee->role}\n";
    echo "  Email Sent At: " . ($employee->confirmation_email_sent_at ?? 'NOT SENT') . "\n";
    echo "  ---\n";
}

echo "\nTotal employees: " . $employees->count() . "\n";
echo "Jobs in queue: " . DB::table('jobs')->count() . "\n";
