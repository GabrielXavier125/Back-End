<?php

use Illuminate\Support\Facades\Route;

Route::get('/pokemon', [App\Http\Controllers\PokemonController::class, 'index'])->name('pokemon.index');
Route::get('/pokemon/random', [App\Http\Controllers\PokemonController::class, 'random'])->name('pokemon.random');

Route::get('/', function () {
    return view('welcome');
});
