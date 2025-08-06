<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Actions\RegisterEmployeeAction;


 // This route handles the public employee registration


Route::post('/employee-registration', RegisterEmployeeAction::class);