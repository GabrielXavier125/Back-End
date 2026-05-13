<?php

namespace App\Filament\Resources\Fornecedors\Schemas;

use App\Models\Fornecedor;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FornecedorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome')
                    ->label('Nome Completo'),
                TextEntry::make('email')
                    ->label('E-mail'),
                TextEntry::make('telefone')
                    ->label('Telefone')
                    ->formatStateUsing(fn (?string $state): string => Fornecedor::formatTelefone($state) ?? '-')
                    ->placeholder('-'),
                TextEntry::make('CNPJ')
                    ->label('CNPJ')
                    ->formatStateUsing(fn (?string $state): string => Fornecedor::formatDocumento($state) ?? '-')
                    ->placeholder('-'),
                TextEntry::make('endereco')
                    ->label('Endereço')
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
