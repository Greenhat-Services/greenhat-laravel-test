<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Course\CreateCourse;

Route::post('/course', CreateCourse::class);
