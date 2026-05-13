<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// Exemplo 1 GET
Route::get('pokemon/{nome}', function ($nome) {
    $response = Http::get("https://pokeapi.co/api/v2/pokemon/$nome");

    if ($response->successful()) {
        $dados = $response->json();
        return response()->json([
            'status' => 'sucesso',
            'resultado' => [
                'identificador' => $dados['id'],
                'nome' => ucfirst($dados['name']),
                'foto' => $dados['sprites']['front_default']
            ]
        ]);
    } else {
        return response()->json(['error' => 'Pokémon não encontrado'], 404);
    }
});

// Exemplo 2 POST
Route::post('pokemon/novo', function (Request $request) {
    $dados = $request->validate([
        'nome' => 'required|string',
        'tipo' => 'required|string',
        'nivel' => 'required|integer'
    ]);

    return response()->json([
        'status' => 'sucesso',
        'id' => rand(1000, 9999),
        'resultado' => [
            'nome' => ucfirst($dados['nome']),
            'tipo' => ucfirst($dados['tipo']),
            'nivel' => $dados['nivel']
        ]
    ], 201);
});