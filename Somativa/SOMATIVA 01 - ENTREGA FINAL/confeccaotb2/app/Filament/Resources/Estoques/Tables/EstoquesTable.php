<?php

namespace App\Filament\Resources\Estoques\Tables;

use App\Models\Estoque;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class EstoquesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantidade')
                    ->label('Quantidade em Estoque')
                    ->numeric()
                    ->sortable()
                    ->color(fn (Estoque $record): string => $record->quantidade < $record->quantidade_minima ? 'danger' : 'success'),
                TextColumn::make('quantidade_minima')
                    ->label('Mínimo')
                    ->numeric()
                    ->sortable()
                    ->color('gray'),
                TextColumn::make('updated_at')
                    ->label('Última Atualização')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordClasses(fn (Estoque $record): string =>
                $record->quantidade < $record->quantidade_minima
                    ? 'border-l-4 !border-l-red-400'
                    : ''
            )
            ->filters([
                TernaryFilter::make('estoque_baixo')
                    ->label('Estoque Baixo')
                    ->trueLabel('Apenas críticos')
                    ->falseLabel('Apenas normais')
                    ->queries(
                        true: fn ($query) => $query->whereColumn('quantidade', '<', 'quantidade_minima'),
                        false: fn ($query) => $query->whereColumn('quantidade', '>=', 'quantidade_minima'),
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
