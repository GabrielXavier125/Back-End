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
                    ->required(),
                Textarea::make('descricao')
                    ->columnSpanFull(),
                TextInput::make('preco')
                    ->required()
                    ->numeric(),
                Select::make('unidade_medida')
                    ->label('Unidade de Medida')
                    ->options([
                        'un' => 'Unidade',
                        'kg' => 'Quilograma',
                        'g' => 'Grama',
                        'l' => 'Litro',
                        'ml' => 'Mililitro',
                        'm' => 'Metro',
                        'cm' => 'Centimetro',
                        'cx' => 'Caixa',
                        'pct' => 'Pacote',
                        'rl' => 'Rolo',
                    ])
                    ->searchable()
                    ->required(),
                TextInput::make('medida'),
                TextInput::make('quantidade'),
            ]);
    }
}
