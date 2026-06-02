<?php

namespace App\Filament\Resources\Estoques;

use App\Filament\Resources\Estoques\Pages\CreateEstoque;
use App\Filament\Resources\Estoques\Pages\EditEstoque;
use App\Filament\Resources\Estoques\Pages\ListEstoques;
use App\Filament\Resources\Estoques\Pages\ViewEstoque;
use App\Filament\Resources\Estoques\Schemas\EstoqueForm;
use App\Filament\Resources\Estoques\Schemas\EstoqueInfolist;
use App\Filament\Resources\Estoques\Tables\EstoquesTable;
use App\Models\Estoque;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EstoqueResource extends Resource
{
    protected static ?string $model = Estoque::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static \UnitEnum|string|null $navigationGroup = 'Operações';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'Estoque';

    public static function form(Schema $schema): Schema
    {
        return EstoqueForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EstoqueInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstoquesTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Estoque::whereColumn('quantidade', '<', 'quantidade_minima')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
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
            'index' => ListEstoques::route('/'),
            'create' => CreateEstoque::route('/create'),
            'view' => ViewEstoque::route('/{record}'),
            'edit' => EditEstoque::route('/{record}/edit'),
        ];
    }
}
