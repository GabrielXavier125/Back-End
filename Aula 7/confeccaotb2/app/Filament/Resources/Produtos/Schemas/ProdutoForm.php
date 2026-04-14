<?php

namespace App\Filament\Resources\Produtos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProdutoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                Textarea::make('descricao')
                    ->columnSpanFull(),
                TextInput::make('preco')
                    ->required()
                    ->numeric(),
                Select::make('fornecedor_id')
                    ->label('Fornecedor')
                    ->relationship('fornecedor', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
            ]);
    }
}
