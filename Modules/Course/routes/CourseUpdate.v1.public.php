<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Course\UpdateCourse;

Route::patch('/Course/{CourseId}', UpdateCourse::class);
