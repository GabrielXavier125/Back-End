<?php

namespace App\Filament\Resources\Permissions\Schemas;

use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PermissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $pageLabels = PermissionResource::pages();

        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nome da Permissão'),

                TextEntry::make('guard_name')
                    ->label('Guard'),

                TextEntry::make('pages')
                    ->label('Páginas Permitidas')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $pageLabels[$state] ?? $state)
                    ->columnSpanFull(),

                TextEntry::make('roles.name')
                    ->label('Cargos')
                    ->badge()
                    ->separator(',')
                    ->columnSpanFull(),
            ]);
    }
}
