<?php

namespace App\Filament\Resources\ItemPedidos\Schemas;

use App\Models\Cliente;
use App\Models\Pedido;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemPedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->label('Cliente')
                    ->options(fn (): array => Cliente::query()->orderBy('nome')->pluck('nome', 'id')->all())
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false)
                    ->native(false),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pendente' => 'Pendente',
                        'em_andamento' => 'Em andamento',
                        'concluido' => 'Concluido',
                        'cancelado' => 'Cancelado',
                    ])
                    ->live()
                    ->dehydrated(false)
                    ->native(false),
                Select::make('pedido_id')
                    ->label('Pedido')
                    ->options(function (callable $get): array {
                        return Pedido::query()
                            ->when($get('cliente_id'), fn ($query, $clienteId) => $query->where('cliente_id', $clienteId))
                            ->when($get('status'), fn ($query, $status) => $query->where('status', $status))
                            ->orderByDesc('id')
                            ->pluck('id', 'id')
                            ->mapWithKeys(fn ($id) => [$id => "Pedido #{$id}"])
                            ->all();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                Select::make('produto_id')
                    ->label('Produto')
                    ->relationship('produto', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                TextInput::make('quantidade')
                    ->required()
                    ->numeric(),
                TextInput::make('preco_unitario')
                    ->required()
                    ->numeric(),
            ]);
    }
}
