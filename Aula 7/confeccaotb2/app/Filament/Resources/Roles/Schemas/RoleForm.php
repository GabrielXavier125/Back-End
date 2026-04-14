<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome do Cargo')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('guard_name')
                    ->label('Guard')
                    ->default('web')
                    ->required()
                    ->maxLength(255),

                CheckboxList::make('permissions')
                    ->label('Permissões do Cargo')
                    ->relationship('permissions', 'name')
                    ->searchable()
                    ->bulkToggleable()
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
