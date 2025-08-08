<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeesRelationManager extends RelationManager
{
    // MUST match Course::employees() relation name
    protected static string $relationship = 'employees';

    protected static ?string $title = 'Enrolled Employees';

    public function form(Form $form): Form
    {
        // Read-only, no form
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('user.email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('pivot.created_at')
                    ->label('Enrolled At')->dateTime()->since()->sortable(),
            ])
            ->headerActions([]) // no attach
            ->actions([])       // no detach/edit/delete
            ->bulkActions([]);  // read-only
    }

    // Extra safety on some Filament versions (instance methods):
    public function canCreate(): bool   { return false; }
    public function canEdit($record): bool   { return false; }
    public function canDelete($record): bool { return false; }
    public function canDeleteAny(): bool     { return false; }
}
