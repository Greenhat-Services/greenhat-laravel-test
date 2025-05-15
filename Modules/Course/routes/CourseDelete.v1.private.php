<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Course\DeleteCourse;

Route::delete('/course/{courseId}', DeleteCourse::class);
