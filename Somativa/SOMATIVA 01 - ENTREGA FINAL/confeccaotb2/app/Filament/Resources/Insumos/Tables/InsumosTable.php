<?php

namespace App\Filament\Resources\Insumos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InsumosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('preco')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('unidade_medida')
                    ->label('Unidade')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantidade')
                    ->label('Quantidade')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('unidade_medida')
                    ->label('Unidade de Medida')
                    ->options([
                        'un'  => 'Unidade',
                        'kg'  => 'Quilograma',
                        'g'   => 'Grama',
                        'l'   => 'Litro',
                        'ml'  => 'Mililitro',
                        'm'   => 'Metro',
                        'cm'  => 'Centímetro',
                        'cx'  => 'Caixa',
                        'pct' => 'Pacote',
                        'rl'  => 'Rolo',
                    ]),
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
