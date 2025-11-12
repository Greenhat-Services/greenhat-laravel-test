<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\CourseCategory\CreateCourseCategory;
use Modules\Course\Actions\CourseCategory\DeleteCourseCategory;
use Modules\Course\Actions\CourseCategory\GetCourseCategories;
use Modules\Course\Actions\CourseCategory\GetCourseCategoryById;
use Modules\Course\Actions\CourseCategory\UpdateCourseCategory;

Route::post('/course-categories', CreateCourseCategory::class);
Route::get('/course-categories', GetCourseCategories::class);
Route::get('/course-categories/{id}', GetCourseCategoryById::class);
Route::put('/course-categories/{id}', UpdateCourseCategory::class);
Route::delete('/course-categories/{id}', DeleteCourseCategory::class);
