<?php

namespace App\Filament\Widgets;

use App\Models\Estoque;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AlertasEstoqueWidget extends BaseWidget
{
    protected static ?string $heading = 'Alertas de Estoque';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Estoque::query()
                    ->with('produto')
                    ->whereColumn('quantidade', '<', 'quantidade_minima')
                    ->orderByRaw('quantidade_minima - quantidade DESC')
            )
            ->columns([
                TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable(),
                TextColumn::make('quantidade')
                    ->label('Qtd. Atual')
                    ->numeric()
                    ->alignCenter()
                    ->color('danger'),
                TextColumn::make('quantidade_minima')
                    ->label('Qtd. Mínima')
                    ->numeric()
                    ->alignCenter()
                    ->color('gray'),
                TextColumn::make('deficit')
                    ->label('Déficit')
                    ->alignCenter()
                    ->state(fn (Estoque $record): int => $record->quantidade_minima - $record->quantidade)
                    ->badge()
                    ->color('danger'),
                TextColumn::make('updated_at')
                    ->label('Última Atualização')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->emptyStateIcon('heroicon-o-check-circle')
            ->emptyStateHeading('Nenhum alerta!')
            ->emptyStateDescription('Todos os produtos estão com estoque acima do mínimo.');
    }
}
