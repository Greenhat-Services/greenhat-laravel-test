<?php

use Modules\Employee\Actions\Employee\CreateEmployeeRegistration;

Route::post('/employee-registration', CreateEmployeeRegistration::class);
