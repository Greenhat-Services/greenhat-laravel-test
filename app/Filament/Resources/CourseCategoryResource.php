<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseCategoryResource\Pages;
use App\Filament\Resources\CourseCategoryResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Course\Models\CourseCategory;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;


class CourseCategoryResource extends Resource
{
    protected static ?string $model = CourseCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Course Management'; // This is the group that the resource will be placed in.

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            // Field for the category's name
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true) // to make sure the name is unique
                ->maxLength(255),

            // Field for the category's description
            Textarea::make('description')
                ->columnSpanFull(), // takes full width
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            // Column for the ID, sortable
            TextColumn::make('id')->sortable(),

            // Column for the name, searchable
            TextColumn::make('name')->searchable(),

            // Column for Uppdate time
            TextColumn::make('updated_at')->dateTime()->sortable(),
        ])
        ->actions([
            EditAction::make(), // Adds an "Edit" button to each row
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(), // Allows bulk deleting
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
            'index' => Pages\ListCourseCategories::route('/'),
            'create' => Pages\CreateCourseCategory::route('/create'),
            'edit' => Pages\EditCourseCategory::route('/{record}/edit'),
        ];
    }
}
