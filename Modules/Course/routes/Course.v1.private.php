<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Course\CreateCourse;
use Modules\Course\Actions\Course\DeleteCourse;
use Modules\Course\Actions\Course\GetCourseById;
use Modules\Course\Actions\Course\GetCourses;
use Modules\Course\Actions\Course\UpdateCourse;

Route::post('/courses', CreateCourse::class);
Route::get('/courses', GetCourses::class);
Route::get('/courses/{id}', GetCourseById::class);
Route::put('/courses/{id}', UpdateCourse::class);
Route::delete('/courses/{id}', DeleteCourse::class);
