<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use App\Models\Produto;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->label('Cliente')
                    ->relationship('cliente', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                Select::make('produto_id')
                    ->label('Produto')
                    ->relationship('produto', 'nome')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get): void {
                        $produto = Produto::find($state);
                        $quantidade = (float) ($get('quantidade') ?? 0);
                        $preco = (float) ($produto?->preco ?? 0);

                        $set('total', $preco * $quantidade);
                    })
                    ->required()
                    ->native(false),
                TextInput::make('quantidade')
                    ->label('Quantidade')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get): void {
                        $produto = Produto::find($get('produto_id'));
                        $quantidade = (float) ($state ?? 0);
                        $preco = (float) ($produto?->preco ?? 0);

                        $set('total', $preco * $quantidade);
                    }),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pendente' => 'Pendente',
                        'em_andamento' => 'Em andamento',
                        'concluido' => 'Concluido',
                        'cancelado' => 'Cancelado',
                    ])
                    ->required()
                    ->default('pendente')
                    ->native(false),
                Placeholder::make('total_calculado')
                    ->label('Valor do Pedido')
                    ->content(function (callable $get): string {
                        $total = (float) ($get('total') ?? 0);

                        return 'R$ ' . number_format($total, 2, ',', '.');
                    }),
                Hidden::make('total')
                    ->default(0)
                    ->required(),
            ]);
    }
}
