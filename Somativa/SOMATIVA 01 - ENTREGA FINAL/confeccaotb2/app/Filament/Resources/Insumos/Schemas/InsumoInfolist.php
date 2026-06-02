<?php

namespace App\Filament\Resources\Insumos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InsumoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome')
                    ->label('Nome do Insumo'),
                TextEntry::make('preco')
                    ->label('Preço')
                    ->money('BRL'),
                TextEntry::make('descricao')
                    ->label('Descrição')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('unidade_medida')
                    ->label('Unidade de Medida')
                    ->placeholder('-'),
                TextEntry::make('medida')
                    ->label('Medida')
                    ->placeholder('-'),
                TextEntry::make('quantidade')
                    ->label('Quantidade')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Cadastrado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
            ]);
    }
}
