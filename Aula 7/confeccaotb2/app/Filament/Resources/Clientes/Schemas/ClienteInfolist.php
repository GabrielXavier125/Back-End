<?php

namespace App\Filament\Resources\Clientes\Schemas;

use App\Models\Cliente;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClienteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('telefone')
                    ->label('Telefone')
                    ->formatStateUsing(fn (?string $state): string => Cliente::formatTelefone($state) ?? '-')
                    ->placeholder('-'),
                TextEntry::make('documento')
                    ->label('CPF / CNPJ')
                    ->formatStateUsing(fn (?string $state): string => Cliente::formatDocumento($state) ?? '-')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
