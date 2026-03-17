<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request) {
        $telefone = $this->onlyDigits($request->input('telefone'));
        $cpf = $this->onlyDigits($request->input('cpf'));

        $clientes = Clientes::query()
            ->when($request->filled('nome'), function ($query) use ($request) {
                $query->where('nome', 'like', '%' . $request->input('nome') . '%');
            })
            ->when($request->filled('email'), function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->input('email') . '%');
            })
            ->when($telefone, function ($query) use ($telefone) {
                $query->where('telefone', 'like', '%' . $telefone . '%');
            })
            ->when($cpf, function ($query) use ($cpf) {
                $query->where('cpf', 'like', '%' . $cpf . '%');
            })
            ->orderBy('nome')
            ->get();

        $filtros = [
            'nome' => $request->input('nome', ''),
            'email' => $request->input('email', ''),
            'telefone' => $request->input('telefone', ''),
            'cpf' => $request->input('cpf', ''),
        ];

        $filtrosAtivos = collect($filtros)->contains(fn ($value) => filled($value));

        return view('clientes.index', compact('clientes', 'filtros', 'filtrosAtivos'));
    }

    // Exibe o formulario de cadastro
    public function create() 
    {
        return view('clientes.create');
    }

    // Recebe os dados do formulario e salva no banco
    public function store(Request $request)
    {
        $request->merge([
            'telefone' => $this->onlyDigits($request->input('telefone')),
            'cpf' => $this->onlyDigits($request->input('cpf')),
        ]);

        // Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'telefone' => 'required|digits_between:10,11',
            'endereco' => 'required|string|max:255',
            'cpf' => 'required|digits:11|unique:clientes,cpf',
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
        $request->merge([
            'telefone' => $this->onlyDigits($request->input('telefone')),
            'cpf' => $this->onlyDigits($request->input('cpf')),
        ]);

        // Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clientes', 'email')->ignore($cliente->id)],
            'telefone' => 'required|digits_between:10,11',
            'endereco' => 'required|string|max:255',
            'cpf' => ['required', 'digits:11', Rule::unique('clientes', 'cpf')->ignore($cliente->id)],
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

    private function onlyDigits(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        return $digits !== '' ? $digits : null;
    }
}