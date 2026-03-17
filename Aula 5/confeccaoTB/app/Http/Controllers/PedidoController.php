<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estoque;
use App\Models\Pedido;
use App\Models\Clientes;
use App\Models\Produto;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['cliente', 'produto'])->get();
        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $clientes = Clientes::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $estoquePorProduto = Estoque::pluck('quantidade', 'produto_id');
        return view('pedidos.create', compact('clientes', 'produtos', 'estoquePorProduto'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'produto_id' => 'required|exists:produtos,id',
            'quantidade'  => 'required|integer|min:1',
            'status'      => 'required|in:pendente,em_andamento,concluido,cancelado',
        ]);

        $estoque = Estoque::where('produto_id', $request->produto_id)->first();
        $disponivel = $estoque ? $estoque->quantidade : 0;

        if ($request->quantidade > $disponivel) {
            return back()->withInput()->withErrors([
                'quantidade' => "Quantidade solicitada ({$request->quantidade}) supera o estoque disponível ({$disponivel} unidades).",
            ]);
        }

        $produto = Produto::findOrFail($request->produto_id);
        $total   = $produto->preco * $request->quantidade;

        Pedido::create([
            'cliente_id' => $request->cliente_id,
            'produto_id' => $request->produto_id,
            'quantidade' => $request->quantidade,
            'total'      => $total,
            'status'     => $request->status,
        ]);

        if ($estoque) {
            $estoque->decrement('quantidade', $request->quantidade);
        }

        return redirect()->route('pedidos.index')->with('success', 'Pedido cadastrado com sucesso!');
    }

    public function edit(Pedido $pedido)
    {
        $clientes = Clientes::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $estoquePorProduto = Estoque::pluck('quantidade', 'produto_id');
        return view('pedidos.edit', compact('pedido', 'clientes', 'produtos', 'estoquePorProduto'));
    }

    public function update(Request $request, Pedido $pedido)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'produto_id' => 'required|exists:produtos,id',
            'quantidade'  => 'required|integer|min:1',
            'status'      => 'required|in:pendente,em_andamento,concluido,cancelado',
        ]);

        $oldProdutoId = $pedido->produto_id;
        $oldQtde      = $pedido->quantidade;
        $novoProdutoId = (int) $request->produto_id;
        $novaQtde      = (int) $request->quantidade;

        $estoqueNovo = Estoque::where('produto_id', $novoProdutoId)->first();
        $disponivelNovo = $estoqueNovo ? $estoqueNovo->quantidade : 0;

        // Se for o mesmo produto, a quantidade atual do pedido retorna ao pool antes de validar
        $disponivelEfetivo = ($oldProdutoId === $novoProdutoId)
            ? $disponivelNovo + $oldQtde
            : $disponivelNovo;

        if ($novaQtde > $disponivelEfetivo) {
            return back()->withInput()->withErrors([
                'quantidade' => "Quantidade solicitada ({$novaQtde}) supera o estoque disponível ({$disponivelEfetivo} unidades).",
            ]);
        }

        // Restaura o estoque do produto antigo
        $estoqueAntigo = Estoque::where('produto_id', $oldProdutoId)->first();
        if ($estoqueAntigo) {
            $estoqueAntigo->increment('quantidade', $oldQtde);
        }

        $produto = Produto::findOrFail($novoProdutoId);
        $total   = $produto->preco * $novaQtde;

        $pedido->update([
            'cliente_id' => $request->cliente_id,
            'produto_id' => $novoProdutoId,
            'quantidade' => $novaQtde,
            'total'      => $total,
            'status'     => $request->status,
        ]);

        // Deduz do estoque do produto novo (que pode ser o mesmo)
        $estoqueNovo->refresh();
        $estoqueNovo->decrement('quantidade', $novaQtde);

        return redirect()->route('pedidos.index')->with('success', 'Pedido atualizado com sucesso!');
    }

    public function destroy(Pedido $pedido)
    {
        $estoque = Estoque::where('produto_id', $pedido->produto_id)->first();
        if ($estoque) {
            $estoque->increment('quantidade', $pedido->quantidade);
        }

        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', 'Pedido excluído com sucesso!');
    }
}

