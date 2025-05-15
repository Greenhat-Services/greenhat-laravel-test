<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Course\UpdateCourse;

Route::patch('/course/{courseId}', UpdateCourse::class);
