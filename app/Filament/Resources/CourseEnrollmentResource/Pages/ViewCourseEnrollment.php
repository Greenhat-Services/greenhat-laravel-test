<?php

namespace App\Filament\Resources\CourseEnrollmentResource\Pages;

use App\Filament\FilamentCustom\Pages\ViewRecord;
use App\Filament\Resources\CourseEnrollmentResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class ViewCourseEnrollment extends ViewRecord
{
    protected static string $resource = CourseEnrollmentResource::class;

    protected static ?string $title = 'View Enrollment';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Enrollment Information')
                    ->schema([
                        Select::make('course_id')
                            ->label('Course')
                            ->relationship('course', 'title')
                            ->disabled()
                            ->columnSpanFull(),

                        Select::make('employee_id')
                            ->label('Employee')
                            ->relationship(
                                'employee',
                                'id',
                                fn ($query) => $query->with('user')
                            )
                            ->getOptionLabelFromRecordUsing(function ($record): string {
                                return $record->user?->name ?? "Employee #{$record->id}";
                            })
                            ->disabled()
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'enrolled' => 'Enrolled',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'dropped' => 'Dropped',
                            ])
                            ->disabled(),

                        TextInput::make('progress_percentage')
                            ->label('Progress (%)')
                            ->disabled()
                            ->suffix('%'),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}

