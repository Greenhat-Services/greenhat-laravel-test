<?php

namespace App\Filament\Resources\CourseEnrollmentResource\Pages;

use App\Filament\FilamentCustom\Pages\ListRecords;
use App\Filament\Resources\CourseEnrollmentResource;

class ListCourseEnrollments extends ListRecords
{
    protected static string $resource = CourseEnrollmentResource::class;
}

