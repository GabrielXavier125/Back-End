<?php

namespace App\Filament\Resources\Estoques\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EstoqueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('produto_id')
                    ->label('Produto')
                    ->relationship('produto', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->native(false),
                TextInput::make('quantidade')
                    ->label('Quantidade em Estoque')
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
            ]);
    }
}
