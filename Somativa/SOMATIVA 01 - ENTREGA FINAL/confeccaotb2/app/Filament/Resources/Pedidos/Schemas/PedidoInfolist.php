<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PedidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('cliente.nome')
                    ->label('Cliente'),
                TextEntry::make('produto.nome')
                    ->label('Produto'),
                TextEntry::make('quantidade')
                    ->label('Quantidade')
                    ->numeric(),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendente'     => 'warning',
                        'em_andamento' => 'info',
                        'concluido'    => 'success',
                        'cancelado'    => 'danger',
                        default        => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendente'     => 'Pendente',
                        'em_andamento' => 'Em andamento',
                        'concluido'    => 'Concluído',
                        'cancelado'    => 'Cancelado',
                        default        => $state,
                    }),
                TextEntry::make('total')
                    ->label('Valor do Pedido')
                    ->money('BRL'),
                TextEntry::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
            ]);
    }
}
