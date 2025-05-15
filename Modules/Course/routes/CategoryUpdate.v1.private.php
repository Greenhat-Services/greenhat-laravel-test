<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Category\UpdateCategory;

Route::patch('/category/{categoryId}', UpdateCategory::class);
