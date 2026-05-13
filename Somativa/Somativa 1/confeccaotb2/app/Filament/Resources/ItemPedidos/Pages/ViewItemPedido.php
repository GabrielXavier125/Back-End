<?php

namespace App\Filament\Resources\ItemPedidos\Pages;

use App\Filament\Resources\ItemPedidos\ItemPedidoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewItemPedido extends ViewRecord
{
    protected static string $resource = ItemPedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
