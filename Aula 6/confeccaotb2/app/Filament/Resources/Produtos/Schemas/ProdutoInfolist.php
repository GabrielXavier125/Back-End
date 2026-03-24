<?php

namespace App\Filament\Resources\Produtos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProdutoInfolist
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
                TextEntry::make('fornecedor.nome')
                    ->label('Fornecedor')
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
