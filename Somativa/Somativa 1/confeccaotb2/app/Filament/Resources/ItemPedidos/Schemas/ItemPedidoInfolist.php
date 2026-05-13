<?php

namespace App\Filament\Resources\ItemPedidos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ItemPedidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('pedido_id')
                    ->numeric(),
                TextEntry::make('produto_id')
                    ->numeric(),
                TextEntry::make('quantidade')
                    ->numeric(),
                TextEntry::make('preco_unitario')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
