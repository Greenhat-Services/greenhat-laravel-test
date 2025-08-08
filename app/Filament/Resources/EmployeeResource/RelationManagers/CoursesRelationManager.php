<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * No form, no actions, just a read-only table
 * since the private API handles enrollment
 *  this is a read-only table
 */

class CoursesRelationManager extends RelationManager
{
    // Should match the relation name on Employee model


    protected static string $relationship = 'courses';

    protected static ?string $title = 'Enrolled Courses';

    public function form(Form $form): Form
    {
        
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'success' => 'published',
                    'warning' => 'draft',
                    'danger'  => 'archived',
                ]),
                Tables\Columns\TextColumn::make('pivot.created_at')
                    ->label('Enrolled At')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            // read-only: no actions
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }

    // extra safety: block create/edit/delete if Filament tries
    public function canCreate(): bool { return false; }
    public function canEdit($record): bool { return false; }
    public function canDelete($record): bool { return false; }
    public function canDeleteAny(): bool { return false; }
}
