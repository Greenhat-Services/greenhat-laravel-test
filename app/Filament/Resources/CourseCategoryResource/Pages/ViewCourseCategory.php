<?php

namespace App\Filament\Resources\CourseCategoryResource\Pages;

use App\Filament\FilamentCustom\Pages\ViewRecord;
use App\Filament\Resources\CourseCategoryResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;

class ViewCourseCategory extends ViewRecord
{
    protected static string $resource = CourseCategoryResource::class;

    protected static ?string $title = 'View Category';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->disabled(),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->disabled(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->disabled(),
                    ]),
            ]);
    }
}

