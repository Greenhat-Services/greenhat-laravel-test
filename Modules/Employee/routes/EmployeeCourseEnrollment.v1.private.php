<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Actions\Enrollment\CreateEmployeeCourseEnrollment;

Route::post('/employee-course-enrollment/{employeeId}', CreateEmployeeCourseEnrollment::class);
