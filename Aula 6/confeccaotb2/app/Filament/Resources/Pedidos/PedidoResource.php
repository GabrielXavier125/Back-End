<?php

namespace App\Filament\Resources\Pedidos;

use App\Filament\Resources\Pedidos\Pages\CreatePedido;
use App\Filament\Resources\Pedidos\Pages\EditPedido;
use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use App\Filament\Resources\Pedidos\Pages\ViewPedido;
use App\Filament\Resources\Pedidos\Schemas\PedidoForm;
use App\Filament\Resources\Pedidos\Schemas\PedidoInfolist;
use App\Filament\Resources\Pedidos\Tables\PedidosTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Pedido';

    public static function form(Schema $schema): Schema
    {
        return PedidoForm::configure($schema);
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->label('Cliente')
                    ->relationship('cliente', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                Select::make('produto_id')
                    ->label('Produto')
                    ->relationship('produto', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                TextInput::make('quantidade')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required(),
                    // ->options([
                    //     'pendente' => 'Pendente',
                    //     'em_andamento' => 'Em Andamento',
                    //     'concluido' => 'Concluído',
                    //     'cancelado' => 'Cancelado',
                    // ]),
                TextInput::make('total')
                    ->required()
                    ->readOnly()
                    ->prefix('R$')
                    ->numeric(),
                Repeater::make('itens')
                    ->label('Itens do Pedido')
                    ->relationship('itens')
                    ->schema([
                        Select::make('produto_id')
                            ->label('Produto')
                            ->relationship('produto', 'nome')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        TextInput::make('quantidade')
                            ->required()
                            ->default(1)
                            ->columnSpan(1)
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::CalcularTotal($get, $set))
                            ->numeric(),
                        TextInput::make('preco_unitario')
                            ->required()
                            ->prefix('R$')
                            ->numeric(),
                    ])
                    ->columnSpan(4)
                    ->columnSpanFull()
                    ->label('Itens do Pedido'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PedidoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedidosTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('cliente.nome')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable(),
                TextColumn::make('quantidade')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'pendente' => 'warning',
                        'em_andamento' => 'primary',
                        'concluido' => 'success',
                        'cancelado' => 'danger',
                        default => 'secondary',
                    }),
                TextColumn::make('total')
                    ->prefix('R$')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListPedidos::route('/'),
            'create' => CreatePedido::route('/create'),
            'view' => ViewPedido::route('/{record}'),
            'edit' => EditPedido::route('/{record}/edit'),
        ];
    }

    public static function CalcularTotal(Get $get, Set $set): void
    {
        $itens = $get('itens') ?? [];
        $total = 0;

        foreach ($itens as $item) {
            $produto = \App\Models\Produto::find($item['produto_id']);
            $precoUnitario = (float) ($produto?->preco ?? 0);
            $quantidade = (float) ($item['quantidade'] ?? 0);
            $total += $precoUnitario * $quantidade;
        }

        $set('total', $total);
    }

}
