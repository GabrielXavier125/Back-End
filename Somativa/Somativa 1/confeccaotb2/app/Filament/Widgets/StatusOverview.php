<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Estoque;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\Pedido;
use App\Models\Produto;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatusOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Resumo do Sistema';

    protected function getStats(): array
    {
        return [
            Stat::make('Clientes', Cliente::count())
                ->description('Cadastros de clientes')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Estoque', Estoque::count())
                ->description('Produtos com controle de estoque')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('warning'),

            Stat::make('Fornecedores', Fornecedor::count())
                ->description('Cadastros de fornecedores')
                ->descriptionIcon('heroicon-o-building-storefront')
                ->color('info'),

            Stat::make('Insumos', Insumo::count())
                ->description('Insumos cadastrados')
                ->descriptionIcon('heroicon-o-beaker')
                ->color('gray'),

            Stat::make('Pedidos', Pedido::count())
                ->description('Pedidos registrados')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('success'),

            Stat::make('Produtos', Produto::count())
                ->description('Produtos cadastrados')
                ->descriptionIcon('heroicon-o-cube')
                ->color('danger'),
        ];
    }
}
