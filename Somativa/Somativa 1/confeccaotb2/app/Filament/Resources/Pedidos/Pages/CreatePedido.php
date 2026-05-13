<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;

    protected function afterCreate(): void
    {
        $pedido = $this->record;

        $preco = (float) ($pedido->produto?->preco ?? 0);
        $quantidade = (float) ($pedido->quantidade ?? 0);
        $total = $quantidade * $preco;

        $pedido->update(['total' => $total]);
    }

}
