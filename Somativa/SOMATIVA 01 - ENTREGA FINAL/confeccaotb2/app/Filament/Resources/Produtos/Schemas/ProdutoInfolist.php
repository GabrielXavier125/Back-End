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
                TextEntry::make('nome')
                    ->label('Nome do Produto'),
                TextEntry::make('fornecedor.nome')
                    ->label('Fornecedor')
                    ->placeholder('-'),
                TextEntry::make('descricao')
                    ->label('Descrição')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('preco')
                    ->label('Preço')
                    ->money('BRL'),
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
