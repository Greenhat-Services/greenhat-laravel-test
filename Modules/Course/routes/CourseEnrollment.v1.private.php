<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\EnrollEmployeeInCourse;

/**
 *  This route handles employee course enrollment.
 * The private visibility naming automatically applies the auth:sanctum middleware.
 * 
 */

 Route::post('/courses/{course}/enroll', EnrollEmployeeInCourse::class)
 ->name('courses.enroll');