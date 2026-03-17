<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Estoque;
use App\Models\Fornecedores;
use App\Models\Pedido;
use App\Models\Produto;

class DashboardController extends Controller
{
    public function index()
    {
        $clientesTotal = Clientes::count();
        $clientesComTelefone = Clientes::whereNotNull('telefone')->where('telefone', '!=', '')->count();
        $clientesSemTelefone = $clientesTotal - $clientesComTelefone;

        $fornecedoresTotal = Fornecedores::count();
        $fornecedoresComTelefone = Fornecedores::whereNotNull('telefone')->where('telefone', '!=', '')->count();
        $fornecedoresSemTelefone = $fornecedoresTotal - $fornecedoresComTelefone;

        $produtosTotal = Produto::count();
        $produtosSemEstoque = Produto::whereNotIn(
            'id',
            Estoque::where('quantidade', '>', 0)->select('produto_id')
        )->count();
        $produtosComEstoque = $produtosTotal - $produtosSemEstoque;

        $estoqueRegistros = Estoque::count();
        $estoqueItensTotal = (int) Estoque::sum('quantidade');
        $estoqueZerado = Estoque::where('quantidade', 0)->count();
        $estoqueBaixo = Estoque::whereBetween('quantidade', [1, 5])->count();
        $estoqueAdequado = Estoque::where('quantidade', '>', 5)->count();
        $estoquePorProduto = Estoque::with('produto')
            ->orderBy('quantidade', 'asc')
            ->get();

        $pedidosTotal = Pedido::count();
        $pedidosPorStatus = Pedido::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pedidosPendente = (int) ($pedidosPorStatus['pendente'] ?? 0);
        $pedidosAndamento = (int) ($pedidosPorStatus['em_andamento'] ?? 0);
        $pedidosConcluido = (int) ($pedidosPorStatus['concluido'] ?? 0);
        $pedidosCancelado = (int) ($pedidosPorStatus['cancelado'] ?? 0);

        if ($pedidosTotal > 0) {
            $pendenteDeg = ($pedidosPendente / $pedidosTotal) * 360;
            $andamentoDeg = ($pedidosAndamento / $pedidosTotal) * 360;
            $concluidoDeg = ($pedidosConcluido / $pedidosTotal) * 360;

            $p1 = $pendenteDeg;
            $p2 = $pendenteDeg + $andamentoDeg;
            $p3 = $pendenteDeg + $andamentoDeg + $concluidoDeg;

            $pedidoChartBackground = sprintf(
                'conic-gradient(#f59e0b 0deg %.2fdeg, #3b82f6 %.2fdeg %.2fdeg, #10b981 %.2fdeg %.2fdeg, #ef4444 %.2fdeg 360deg)',
                $p1,
                $p1,
                $p2,
                $p2,
                $p3,
                $p3
            );
        } else {
            $pedidoChartBackground = 'conic-gradient(#e5e7eb 0deg 360deg)';
        }

        $faturamentoTotal = (float) Pedido::sum('total');

        return view('dashboard', [
            'clientesTotal' => $clientesTotal,
            'clientesComTelefone' => $clientesComTelefone,
            'clientesSemTelefone' => $clientesSemTelefone,
            'fornecedoresTotal' => $fornecedoresTotal,
            'fornecedoresComTelefone' => $fornecedoresComTelefone,
            'fornecedoresSemTelefone' => $fornecedoresSemTelefone,
            'produtosTotal' => $produtosTotal,
            'produtosComEstoque' => $produtosComEstoque,
            'produtosSemEstoque' => $produtosSemEstoque,
            'estoqueRegistros' => $estoqueRegistros,
            'estoqueItensTotal' => $estoqueItensTotal,
            'estoqueZerado' => $estoqueZerado,
            'estoqueBaixo' => $estoqueBaixo,
            'estoqueAdequado' => $estoqueAdequado,
            'estoquePorProduto' => $estoquePorProduto,
            'pedidosTotal' => $pedidosTotal,
            'pedidosPendente' => $pedidosPendente,
            'pedidosAndamento' => $pedidosAndamento,
            'pedidosConcluido' => $pedidosConcluido,
            'pedidosCancelado' => $pedidosCancelado,
            'pedidoChartBackground' => $pedidoChartBackground,
            'faturamentoTotal' => $faturamentoTotal,
        ]);
    }
}