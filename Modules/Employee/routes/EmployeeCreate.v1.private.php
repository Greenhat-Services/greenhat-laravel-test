<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Actions\Employee\CreateEmployee;

Route::post('/employee', CreateEmployee::class);
