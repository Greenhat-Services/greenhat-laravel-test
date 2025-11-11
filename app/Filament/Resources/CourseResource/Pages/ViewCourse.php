<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\FilamentCustom\Pages\ViewRecord;
use App\Filament\Resources\CourseResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    protected static ?string $title = 'View Course';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Course Information')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->disabled(),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->disabled(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->disabled()
                            ->columnSpanFull(),

                        TextInput::make('instructor')
                            ->label('Instructor')
                            ->disabled(),

                        TextInput::make('duration_hours')
                            ->label('Duration (Hours)')
                            ->disabled(),

                        Select::make('difficulty_level')
                            ->label('Difficulty Level')
                            ->options([
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                            ])
                            ->disabled(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->disabled(),

                        TextInput::make('max_enrollments')
                            ->label('Max Enrollments')
                            ->disabled(),

                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->disabled(),

                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->disabled(),

                        Select::make('categories')
                            ->label('Categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}

