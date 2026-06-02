<?php

namespace App\Filament\Resources\Insumos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InsumoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->label('Nome do Insumo')
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
                Select::make('unidade_medida')
                    ->label('Unidade de Medida')
                    ->options([
                        'un'  => 'Unidade',
                        'kg'  => 'Quilograma',
                        'g'   => 'Grama',
                        'l'   => 'Litro',
                        'ml'  => 'Mililitro',
                        'm'   => 'Metro',
                        'cm'  => 'Centímetro',
                        'cx'  => 'Caixa',
                        'pct' => 'Pacote',
                        'rl'  => 'Rolo',
                    ])
                    ->searchable()
                    ->required()
                    ->native(false),
                TextInput::make('medida')
                    ->label('Medida')
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01),
                TextInput::make('quantidade')
                    ->label('Quantidade')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
            ]);
    }
}
