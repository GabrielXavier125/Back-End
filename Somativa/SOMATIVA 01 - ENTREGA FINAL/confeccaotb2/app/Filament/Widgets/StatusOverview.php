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
        $pedidosPendentes   = Pedido::where('status', 'pendente')->count();
        $pedidosEmAndamento = Pedido::where('status', 'em_andamento')->count();
        $pedidosConcluidos  = Pedido::where('status', 'concluido')->count();
        $totalFaturado      = Pedido::where('status', 'concluido')->sum('total');

        return [
            Stat::make('Pedidos Pendentes', $pedidosPendentes)
                ->description('Aguardando processamento')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Em Andamento', $pedidosEmAndamento)
                ->description('Pedidos em produção')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('info'),

            Stat::make('Concluídos', $pedidosConcluidos)
                ->description('Pedidos finalizados')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Total Faturado', 'R$ ' . number_format($totalFaturado, 2, ',', '.'))
                ->description('Soma dos pedidos concluídos')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Clientes', Cliente::count())
                ->description('Clientes cadastrados')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Produtos em Estoque', Estoque::sum('quantidade'))
                ->description('Unidades disponíveis')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('gray'),
        ];
    }
}
