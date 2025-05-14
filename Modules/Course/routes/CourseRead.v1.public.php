<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Course\GetCourse;

Route::get('/course/{CourseId}', GetCourse::class);
