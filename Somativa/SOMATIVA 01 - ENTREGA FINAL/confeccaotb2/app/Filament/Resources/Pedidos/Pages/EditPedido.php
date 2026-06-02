<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use App\Models\Estoque;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPedido extends EditRecord
{
    protected static string $resource = PedidoResource::class;

    protected int $originalProdutoId;
    protected int $originalQuantidade;
    protected string $originalStatus;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $pedido = $this->record;

        $this->originalProdutoId  = (int) $pedido->produto_id;
        $this->originalQuantidade = (int) $pedido->quantidade;
        $this->originalStatus     = (string) $pedido->status;

        $data          = $this->form->getState();
        $novoProdutoId = (int) $data['produto_id'];
        $novaQtd       = (int) ($data['quantidade'] ?? 0);

        $estoque      = Estoque::where('produto_id', $novoProdutoId)->first();
        $estoqueAtual = $estoque?->quantidade ?? null;

        if ($estoqueAtual !== null) {
            // Se o produto não mudou, devolve a qtd original antes de comparar
            $disponivel = ($novoProdutoId === $this->originalProdutoId)
                ? $estoqueAtual + $this->originalQuantidade
                : $estoqueAtual;

            if ($novaQtd > $disponivel) {
                Notification::make()
                    ->title('Estoque insuficiente')
                    ->body("Disponível para este produto: {$disponivel} unidade(s).")
                    ->danger()
                    ->send();

                $this->halt();
            }
        }
    }

    protected function afterSave(): void
    {
        $pedido      = $this->record->fresh();
        $novoStatus  = $pedido->status;
        $eraConc     = $this->originalStatus === 'concluido';
        $ficouConc   = $novoStatus === 'concluido';

        if ($eraConc && !$ficouConc) {
            // Saiu de Concluído → restaura o estoque original
            Estoque::where('produto_id', $this->originalProdutoId)
                ->increment('quantidade', $this->originalQuantidade);

        } elseif (!$eraConc && $ficouConc) {
            // Passou para Concluído → desconta o estoque novo
            Estoque::where('produto_id', $pedido->produto_id)
                ->decrement('quantidade', $pedido->quantidade);

        } elseif ($eraConc && $ficouConc) {
            // Já era e continua Concluído → ajusta diferença (produto ou qtd mudou)
            Estoque::where('produto_id', $this->originalProdutoId)
                ->increment('quantidade', $this->originalQuantidade);
            Estoque::where('produto_id', $pedido->produto_id)
                ->decrement('quantidade', $pedido->quantidade);
        }
        // Se não era e não ficou Concluído → nenhuma movimentação de estoque

        // Recalcula o total
        $preco = (float) ($pedido->produto?->preco ?? 0);
        $pedido->update(['total' => $pedido->quantidade * $preco]);
    }
}
