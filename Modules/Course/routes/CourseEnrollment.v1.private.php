<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Course\EnrollEmployee;
use Modules\Course\Actions\Course\GetEmployeeEnrollments;
use Modules\Course\Actions\Course\UnenrollEmployee;

Route::post('/course-enrollments', EnrollEmployee::class);
Route::get('/course-enrollments', GetEmployeeEnrollments::class);
Route::delete('/course-enrollments/{id}', UnenrollEmployee::class);
