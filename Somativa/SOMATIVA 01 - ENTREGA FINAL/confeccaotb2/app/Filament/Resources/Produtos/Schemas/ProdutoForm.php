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
                    ->label('Nome do Produto')
                    ->required()
                    ->maxLength(255),
                Textarea::make('descricao')
                    ->label('Descrição')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('preco')
                    ->label('Preço (R$)')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->minValue(0)
                    ->step(0.01),
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
