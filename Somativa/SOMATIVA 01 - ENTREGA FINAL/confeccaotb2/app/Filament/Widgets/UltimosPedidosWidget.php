<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimosPedidosWidget extends BaseWidget
{
    protected static ?string $heading = 'Últimos Pedidos';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pedido::query()->with(['cliente', 'produto'])->latest()->limit(8)
            )
            ->columns([
                TextColumn::make('cliente.nome')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable(),
                TextColumn::make('quantidade')
                    ->label('Qtd.')
                    ->alignCenter(),
                TextColumn::make('status')
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
                TextColumn::make('total')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }
}
