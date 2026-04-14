<?php

namespace App\Filament\Resources\Roles\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Funcionários';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Desde')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->recordActions([
                DetachAction::make()->label('Remover do Cargo'),
            ])
            ->toolbarActions([
                AttachAction::make()
                    ->label('Vincular Funcionário')
                    ->preloadRecordSelect(),
                BulkActionGroup::make([
                    DetachBulkAction::make()->label('Remover Selecionados'),
                ]),
            ]);
    }
}
