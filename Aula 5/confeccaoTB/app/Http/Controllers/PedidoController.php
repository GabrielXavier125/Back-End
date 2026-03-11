<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('pedidos.create', compact('clientes', 'produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'produto_id' => 'required|exists:produtos,id',
            'quantidade'  => 'required|integer|min:1',
            'status'      => 'required|in:pendente,em_andamento,concluido,cancelado',
        ]);

        $produto = Produto::findOrFail($request->produto_id);
        $total   = $produto->preco * $request->quantidade;

        Pedido::create([
            'cliente_id' => $request->cliente_id,
            'produto_id' => $request->produto_id,
            'quantidade' => $request->quantidade,
            'total'      => $total,
            'status'     => $request->status,
        ]);

        return redirect()->route('pedidos.index')->with('success', 'Pedido cadastrado com sucesso!');
    }

    public function edit(Pedido $pedido)
    {
        $clientes = Clientes::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        return view('pedidos.edit', compact('pedido', 'clientes', 'produtos'));
    }

    public function update(Request $request, Pedido $pedido)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'produto_id' => 'required|exists:produtos,id',
            'quantidade'  => 'required|integer|min:1',
            'status'      => 'required|in:pendente,em_andamento,concluido,cancelado',
        ]);

        $produto = Produto::findOrFail($request->produto_id);
        $total   = $produto->preco * $request->quantidade;

        $pedido->update([
            'cliente_id' => $request->cliente_id,
            'produto_id' => $request->produto_id,
            'quantidade' => $request->quantidade,
            'total'      => $total,
            'status'     => $request->status,
        ]);

        return redirect()->route('pedidos.index')->with('success', 'Pedido atualizado com sucesso!');
    }

    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', 'Pedido excluído com sucesso!');
    }
}

