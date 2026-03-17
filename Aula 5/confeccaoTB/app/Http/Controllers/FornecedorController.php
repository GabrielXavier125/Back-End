<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedores;
use Illuminate\Validation\Rule;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $telefone = $this->onlyDigits($request->input('telefone'));
        $cnpj = $this->onlyDigits($request->input('cnpj'));

        $fornecedores = Fornecedores::query()
            ->when($request->filled('nome'), function ($query) use ($request) {
                $query->where('nome', 'like', '%' . $request->input('nome') . '%');
            })
            ->when($request->filled('email'), function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->input('email') . '%');
            })
            ->when($telefone, function ($query) use ($telefone) {
                $query->where('telefone', 'like', '%' . $telefone . '%');
            })
            ->when($cnpj, function ($query) use ($cnpj) {
                $query->where('cnpj', 'like', '%' . $cnpj . '%');
            })
            ->orderBy('nome')
            ->get();

        $filtros = [
            'nome' => $request->input('nome', ''),
            'email' => $request->input('email', ''),
            'telefone' => $request->input('telefone', ''),
            'cnpj' => $request->input('cnpj', ''),
        ];

        $filtrosAtivos = collect($filtros)->contains(fn ($value) => filled($value));

        return view('fornecedores.index', compact('fornecedores', 'filtros', 'filtrosAtivos'));
    }

    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'telefone' => $this->onlyDigits($request->input('telefone')),
            'cnpj' => $this->onlyDigits($request->input('cnpj')),
        ]);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:fornecedores,email',
            'telefone' => 'nullable|digits_between:10,11',
            'cnpj' => 'required|digits:14|unique:fornecedores,cnpj',
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
        $request->merge([
            'telefone' => $this->onlyDigits($request->input('telefone')),
            'cnpj' => $this->onlyDigits($request->input('cnpj')),
        ]);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('fornecedores', 'email')->ignore($fornecedor->id)],
            'telefone' => 'nullable|digits_between:10,11',
            'cnpj' => ['required', 'digits:14', Rule::unique('fornecedores', 'cnpj')->ignore($fornecedor->id)],
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

    private function onlyDigits(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        return $digits !== '' ? $digits : null;
    }
}
