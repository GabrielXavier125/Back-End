<?php

namespace App\Filament\Resources\Insumos;

use App\Filament\Resources\Insumos\Pages\CreateInsumo;
use App\Filament\Resources\Insumos\Pages\EditInsumo;
use App\Filament\Resources\Insumos\Pages\ListInsumos;
use App\Filament\Resources\Insumos\Pages\ViewInsumo;
use App\Filament\Resources\Insumos\Schemas\InsumoForm;
use App\Filament\Resources\Insumos\Schemas\InsumoInfolist;
use App\Filament\Resources\Insumos\Tables\InsumosTable;
use App\Models\Insumo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InsumoResource extends Resource
{
    protected static ?string $model = Insumo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Insumo';

    public static function form(Schema $schema): Schema
    {
        return InsumoForm::configure($schema);
        return $schema
            ->schema([
                TextInput::make('nome')
                    ->required()
                    ->label('Nome do Insumo')
                    ->maxLength(255),
                TextInput::make('descricao')
                    ->label('Descrição')
                    ->maxLength(500),
                TextInput::make('preco')
                    ->label('Preço')
                    ->numeric()
                    ->prefix('R$')
                    ->step(0.01),
                TextInput::make('unidade_medida')
                    ->label('Unidade de Medida')
                    ->maxLength(50),
                TextInput::make('medida')
                    ->label('Medida')
                    ->maxLength(50),
                TextInput::make('quantidade')
                    ->label('Quantidade')
                    ->maxLength(50),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InsumoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InsumosTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('nome')->label('Nome do Insumo'),
                TextColumn::make('descricao')->label('Descrição'),
                TextColumn::make('preco')->label('Preço')->money('BRL', true),
                TextColumn::make('unidade_medida')->label('Unidade de Medida'),
                TextColumn::make('medida')->label('Medida'),
                TextColumn::make('quantidade')->label('Quantidade'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInsumos::route('/'),
            'create' => CreateInsumo::route('/create'),
            'view' => ViewInsumo::route('/{record}'),
            'edit' => EditInsumo::route('/{record}/edit'),
        ];
    }
}
