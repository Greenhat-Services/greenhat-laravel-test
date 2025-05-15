<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Category\CreateCategory;

Route::post('/category', CreateCategory::class);
