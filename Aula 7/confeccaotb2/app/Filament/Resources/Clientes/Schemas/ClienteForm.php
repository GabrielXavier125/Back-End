<?php

namespace App\Filament\Resources\Clientes\Schemas;

use App\Models\Cliente;
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
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('telefone')
                    ->tel()
                    ->label('Telefone')
                    ->maxLength(15)
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        if ($state) {
                            $component->state(Cliente::formatTelefone($state));
                        }
                    })
                    ->mask(RawJs::make(<<<'JS'
                        $input.replace(/\D/g, '').length > 10 ? '(99) 99999-9999' : '(99) 9999-9999'
                    JS)),
                TextInput::make('documento')
                    ->label('CPF / CNPJ')
                    ->maxLength(18)
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        if ($state) {
                            $component->state(Cliente::formatDocumento($state));
                        }
                    })
                    ->mask(RawJs::make(<<<'JS'
                        $input.replace(/\D/g, '').length > 11 ? '99.999.999/9999-99' : '999.999.999-99'
                    JS)),
            ]);
    }
}
