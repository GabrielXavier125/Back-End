<?php

namespace App\Filament\Resources\Fornecedors\Tables;

use App\Models\Fornecedor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FornecedorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome Completo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telefone')
                    ->label('Telefone')
                    ->formatStateUsing(fn (?string $state): string => Fornecedor::formatTelefone($state) ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('CNPJ')
                    ->label('CNPJ')
                    ->formatStateUsing(fn (?string $state): string => Fornecedor::formatDocumento($state) ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('endereco')
                    ->label('Endereço')
                    ->searchable()
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
                //
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
