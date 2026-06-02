<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use App\Models\Estoque;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;

    protected function beforeCreate(): void
    {
        $data       = $this->form->getState();
        $produtoId  = $data['produto_id'];
        $quantidade = (int) ($data['quantidade'] ?? 0);
        $status     = $data['status'] ?? 'pendente';

        // Só valida estoque se o pedido já for criado como Concluído
        if ($status !== 'concluido') {
            return;
        }

        $estoque = Estoque::where('produto_id', $produtoId)->first();

        if ($estoque && $estoque->quantidade < $quantidade) {
            Notification::make()
                ->title('Estoque insuficiente')
                ->body("Disponível em estoque: {$estoque->quantidade} unidade(s). Pedido não criado.")
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function afterCreate(): void
    {
        $pedido = $this->record;

        $preco      = (float) ($pedido->produto?->preco ?? 0);
        $quantidade = (float) ($pedido->quantidade ?? 0);
        $pedido->update(['total' => $quantidade * $preco]);

        // Desconta estoque apenas se o pedido já for criado como Concluído
        if ($pedido->status === 'concluido') {
            Estoque::where('produto_id', $pedido->produto_id)
                ->decrement('quantidade', $pedido->quantidade);
        }
    }
}
