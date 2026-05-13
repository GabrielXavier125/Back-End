<?php

namespace App\Filament\Resources\Clientes;

use App\Filament\Resources\Clientes\Pages\CreateCliente;
use App\Filament\Resources\Clientes\Pages\EditCliente;
use App\Filament\Resources\Clientes\Pages\ListClientes;
use App\Filament\Resources\Clientes\Pages\ViewCliente;
use App\Filament\Resources\Clientes\Schemas\ClienteForm;
use App\Filament\Resources\Clientes\Schemas\ClienteInfolist;
use App\Filament\Resources\Clientes\Tables\ClientesTable;
use App\Models\Cliente;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Clientes';

    public static function form(Schema $schema): Schema
    {
        return ClienteForm::configure($schema);
        return $schema
            ->schema([
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
                    ->maxLength(20)
                    ->mask('(00) 00000-0000'),
                TextInput::make('documento')
                    ->label('CPF / CNPJ')
                    ->mask(RawJs::make(<<<'JS'
                        $input.length > 14 ? '99.999.999/9999-99' : '999.999.999-99'
                        JS))
                    ->maxLength(50),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClienteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientesTable::configure($table);
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
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('documento')
                        ->label('CPF / CNPJ')
                        ->searchable()
                        ->sortable(),
                ])
                ->defaultSort('nome');
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
            'index' => ListClientes::route('/'),
            'create' => CreateCliente::route('/create'),
            'view' => ViewCliente::route('/{record}'),
            'edit' => EditCliente::route('/{record}/edit'),
        ];
    }
}
