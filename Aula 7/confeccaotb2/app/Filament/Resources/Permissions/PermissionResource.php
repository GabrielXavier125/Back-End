<?php

namespace App\Filament\Resources\Permissions;

use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\Pages\ViewPermission;
use App\Filament\Resources\Permissions\Schemas\PermissionInfolist;
use App\Models\Permission;
use App\Traits\HasPagePermission;
use BackedEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    use HasPagePermission;

    protected static ?string $model = Permission::class;

    protected static string $pageKey = 'permissoes';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'Permissões';

    protected static ?string $modelLabel = 'Permissão';

    protected static ?string $pluralModelLabel = 'Permissões';

    public static function getNavigationGroup(): ?string
    {
        return 'Gerenciamento de Acesso';
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function pages(): array
    {
        return [
            'clientes'     => 'Clientes',
            'pedidos'      => 'Pedidos',
            'produtos'     => 'Produtos',
            'insumos'      => 'Insumos',
            'estoques'     => 'Estoques',
            'fornecedores' => 'Fornecedores',
            'cargos'       => 'Cargos',
            'permissoes'   => 'Permissões',
            'usuarios'     => 'Usuários',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Identificação')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome da Permissão')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('guard_name')
                            ->label('Guard')
                            ->default('web')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Páginas Permitidas')
                    ->description('Selecione quais páginas os usuários com esta permissão poderão acessar.')
                    ->schema([
                        CheckboxList::make('pages')
                            ->label('')
                            ->options(static::pages())
                            ->columns(3)
                            ->bulkToggleable()
                            ->columnSpanFull(),
                    ]),

                Section::make('Cargos')
                    ->description('Cargos que possuem esta permissão.')
                    ->schema([
                        CheckboxList::make('roles')
                            ->label('')
                            ->relationship('roles', 'name')
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PermissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome da Permissão')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles_count')
                    ->label('Cargos')
                    ->counts('roles')
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'view'   => ViewPermission::route('/{record}'),
            'edit'   => EditPermission::route('/{record}/edit'),
        ];
    }
}
