<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedores;
use Illuminate\Validation\Rule;

class FornecedorController extends Controller
{
    public function index()
    {
        $fornecedores = \App\Models\Fornecedores::all();
        return view('fornecedores.index', compact('fornecedores'));
    }

    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:fornecedores,email',
            'telefone' => 'nullable|string|max:20',
            'cnpj' => 'required|string|max:18|unique:fornecedores,cnpj',
            'endereco' => 'nullable|string|max:255',
        ]);

        Fornecedores::create($request->all());

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit(Fornecedores $fornecedor)
    {
        return view('fornecedores.edit', compact('fornecedor'));
    }

    public function update(Request $request, Fornecedores $fornecedor)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('fornecedores', 'email')->ignore($fornecedor->id)],
            'telefone' => 'nullable|string|max:20',
            'cnpj' => ['required', 'string', 'max:18', Rule::unique('fornecedores', 'cnpj')->ignore($fornecedor->id)],
            'endereco' => 'nullable|string|max:255',
        ]);

        $fornecedor->update($request->all());

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedores $fornecedor)
    {
        $fornecedor->delete();

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor excluído com sucesso!');
    }
}
