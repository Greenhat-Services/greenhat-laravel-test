<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Actions\Employee\UpdateEmployee;

Route::patch('/employee/{employeeId}', UpdateEmployee::class);
