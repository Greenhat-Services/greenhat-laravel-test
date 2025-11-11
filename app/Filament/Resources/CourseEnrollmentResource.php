<?php

namespace App\Filament\Resources;

use App\Filament\FilamentCustom\Tables\Actions\ViewAction;
use App\Filament\FilamentCustom\Tables\Columns\DateTimeColumn;
use App\Filament\Resources\CourseEnrollmentResource\Pages;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseEnrollment;
use Modules\Employee\Models\Employee;

class CourseEnrollmentResource extends Resource
{
    protected static ?string $model = CourseEnrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Courses';

    protected static ?string $navigationLabel = 'Enrollments';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Enrollment Information')
                    ->schema([
                        Select::make('course_id')
                            ->label('Course')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull()
                            ->live()
                            ->rules([
                                fn ($get, $record) => function (string $attribute, $value, $fail) use ($get, $record) {
                                    $courseId = $value;
                                    $employeeId = $get('employee_id');

                                    if (!$employeeId) {
                                        return; // Skip if employee not selected yet
                                    }

                                    // Skip validation if editing the same record
                                    $query = CourseEnrollment::where('course_id', $courseId)
                                        ->where('employee_id', $employeeId);

                                    if ($record) {
                                        $query->where('id', '!=', $record->id);
                                    }

                                    if ($query->exists()) {
                                        $fail('This employee is already enrolled in this course.');
                                    }
                                },
                            ]),

                        Select::make('employee_id')
                            ->label('Employee')
                            ->relationship(
                                'employee',
                                'id',
                                fn ($query) => $query->with('user')
                            )
                            ->getOptionLabelFromRecordUsing(function (Employee $record): string {
                                return $record->user?->name ?? "Employee #{$record->id}";
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull()
                            ->live()
                            ->rules([
                                fn ($get, $record) => function (string $attribute, $value, $fail) use ($get, $record) {
                                    $courseId = $get('course_id');
                                    $employeeId = $value;

                                    if (!$courseId) {
                                        return; // Skip if course not selected yet
                                    }

                                    // Skip validation if editing the same record
                                    $query = CourseEnrollment::where('course_id', $courseId)
                                        ->where('employee_id', $employeeId);

                                    if ($record) {
                                        $query->where('id', '!=', $record->id);
                                    }

                                    if ($query->exists()) {
                                        $fail('This employee is already enrolled in this course.');
                                    }
                                },
                            ]),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'enrolled' => 'Enrolled',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'dropped' => 'Dropped',
                            ])
                            ->required()
                            ->default('enrolled'),

                        TextInput::make('progress_percentage')
                            ->label('Progress (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%'),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('enrolled_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('employee.user.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable()
                    ->default('N/A'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        'enrolled' => 'info',
                        'dropped' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->sortable(),

                TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->suffix('%')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 100 => 'success',
                        $state >= 50 => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                DateTimeColumn::make('enrolled_at')
                    ->label('Enrolled At')
                    ->sortable(),

                DateTimeColumn::make('completed_at')
                    ->label('Completed At')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                DateTimeColumn::make('created_at')
                    ->label('Created At')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'enrolled' => 'Enrolled',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'dropped' => 'Dropped',
                    ]),

                SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship(
                        'employee',
                        'id',
                        fn ($query) => $query->with('user')
                    )
                    ->getOptionLabelFromRecordUsing(function (Employee $record): string {
                        return $record->user?->name ?? "Employee #{$record->id}";
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                ViewAction::make(),
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseEnrollments::route('/'),
            'create' => Pages\CreateCourseEnrollment::route('/create'),
            'view' => Pages\ViewCourseEnrollment::route('/{record}'),
            'edit' => Pages\EditCourseEnrollment::route('/{record}/edit'),
        ];
    }
}

