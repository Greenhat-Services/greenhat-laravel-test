<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\Concerns\HasRelationManagers;

class ViewCourse extends ViewRecord
{
    use HasRelationManagers;

    protected static string $resource = CourseResource::class;
}