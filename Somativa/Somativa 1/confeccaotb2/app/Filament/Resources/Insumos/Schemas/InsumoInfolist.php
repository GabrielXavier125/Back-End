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
                TextEntry::make('nome'),
                TextEntry::make('descricao')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('preco')
                    ->numeric(),
                TextEntry::make('unidade_medida')
                    ->placeholder('-'),
                TextEntry::make('medida')
                    ->placeholder('-'),
                TextEntry::make('quantidade')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
