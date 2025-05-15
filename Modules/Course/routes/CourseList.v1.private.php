<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Course\ListCourse;

Route::get('/course', ListCourse::class);
