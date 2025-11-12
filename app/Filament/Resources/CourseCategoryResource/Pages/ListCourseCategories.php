<?php

namespace App\Filament\Resources\CourseCategoryResource\Pages;

use App\Filament\FilamentCustom\Pages\ListRecords;
use App\Filament\Resources\CourseCategoryResource;

class ListCourseCategories extends ListRecords
{
    protected static string $resource = CourseCategoryResource::class;
}

