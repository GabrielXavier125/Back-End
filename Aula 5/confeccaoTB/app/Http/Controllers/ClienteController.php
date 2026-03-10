<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index() {
        $clientes = \App\Models\Clientes::all();
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
        \App\Models\Clientes::create($request->all());

        // Redireciona para a lista de clientes
        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }
}
