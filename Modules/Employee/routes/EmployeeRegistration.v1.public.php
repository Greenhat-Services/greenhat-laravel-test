<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Actions\RegisterEmployeeAction;


 // This route handles the public employee registration


 Route::post('/employee-registration', RegisterEmployeeAction::class)
 ->middleware('throttle:10,1'); // Rate limiting to 10 requests per minute, makes sure the endpoint is not abused

