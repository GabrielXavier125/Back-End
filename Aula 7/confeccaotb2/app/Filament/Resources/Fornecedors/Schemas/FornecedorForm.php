<?php

namespace App\Filament\Resources\Fornecedors\Schemas;

use App\Models\Fornecedor;
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
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        if ($state) {
                            $component->state(Fornecedor::formatTelefone($state));
                        }
                    })
                    ->mask(RawJs::make(<<<'JS'
                        $input.replace(/\D/g, '').length > 10 ? '(99) 99999-9999' : '(99) 9999-9999'
                    JS)),
                TextInput::make('CNPJ')
                    ->label('CNPJ')
                    ->maxLength(18)
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        if ($state) {
                            $component->state(Fornecedor::formatDocumento($state));
                        }
                    })
                    ->mask('99.999.999/9999-99'),
                TextInput::make('endereco')
                    ->label('Endereço')
                    ->maxLength(255),
            ]);
    }
}
