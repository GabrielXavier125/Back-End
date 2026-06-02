<?php

namespace App\Filament\Resources\Fornecedors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class FornecedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required()
                    ->label('Nome Completo')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('E-mail'),
                TextInput::make('telefone')
                    ->tel()
                    ->label('Telefone')
                    ->maxLength(15)
                    ->mask(RawJs::make('$input.length >= 15 ? "(00) 00000-0000" : "(00) 0000-00009"')),
                TextInput::make('CNPJ')
                    ->label('CNPJ')
                    ->maxLength(18)
                    ->mask(RawJs::make('"00.000.000/0000-00"')),
                TextInput::make('endereco')
                    ->label('Endereço')
                    ->maxLength(255),
            ]);
    }
}
