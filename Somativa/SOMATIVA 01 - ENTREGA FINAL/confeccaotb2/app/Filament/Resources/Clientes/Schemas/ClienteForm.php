<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->label('Nome Completo')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('telefone')
                    ->label('Telefone')
                    ->tel()
                    ->maxLength(15)
                    ->mask(RawJs::make('$input.length >= 15 ? "(00) 00000-0000" : "(00) 0000-00009"')),
                TextInput::make('documento')
                    ->label('CPF / CNPJ')
                    ->maxLength(18)
                    ->mask(RawJs::make('$input.length > 14 ? "00.000.000/0000-00" : "000.000.000-009"')),
            ]);
    }
}
