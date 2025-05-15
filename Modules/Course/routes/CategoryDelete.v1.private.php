<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Actions\Category\DeleteCategory;

Route::delete('/category/{categoryId}', DeleteCategory::class);
