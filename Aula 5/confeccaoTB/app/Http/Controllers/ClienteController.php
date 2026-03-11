<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index() {
        $clientes = Clientes::all();
        return view('clientes.index', compact('clientes'));
    }

    // Exibe o formulario de cadastro
    public function create() 
    {
        return view('clientes.create');
    }

    // Recebe os dados do formulario e salva no banco
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'telefone' => 'required|string|max:20',
            'endereco' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:clientes,cpf',
        ]);

        // Cria o cliente
        // O modelo é "Clientes" (plural), portanto é preciso usá‑lo aqui.
        Clientes::create($request->all());

        // Redireciona para a lista de clientes
        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    // Exibe o formulario de edição
    public function edit(Clientes $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    // Atualiza os dados do cliente
    public function update(Request $request, Clientes $cliente)
    {
        // Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clientes', 'email')->ignore($cliente->id)],
            'telefone' => 'required|string|max:20',
            'endereco' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'max:14', Rule::unique('clientes', 'cpf')->ignore($cliente->id)],
        ]);

        // Atualiza o cliente
        $cliente->update($request->all());

        // Redireciona para a lista de clientes
        return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
    }


    // Exclui um cliente
    public function destroy(Clientes $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso!');
    }
}