<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        $pokemonName = $request->query('name');
        
        // Se não tiver nome, busca um aleatório
        if (empty($pokemonName)) {
            $id = rand(1, 151);
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$id}");
        } else {
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/" . strtolower($pokemonName));
        }
        
        if ($response->successful()) {
            $pokemon = $response->json();
            return view('pokemon', compact('pokemon'));
        }
        return view('pokemon', ['error' => 'Pokémon não encontrado']);
    }

    public function random()
    {
        $id = rand(1, 151);
        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$id}");
        
        if ($response->successful()) {
            $pokemon = $response->json();
            return view('pokemon', compact('pokemon'));
        }
        return view('pokemon', ['error' => 'Pokémon não encontrado']);
    }
}