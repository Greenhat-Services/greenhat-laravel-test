<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Category\GetCategory;

Route::get('/category/{categoryId}', GetCategory::class);
