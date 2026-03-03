<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/empresa', function() {
    return view('empresa');
});

Route::get('/view', function(){
    return view('view');
});

Route::any('/liberado', function () {
    return "Acesso permitido put, delete, get, post";
});

Route::match(['get', 'post'],'/bloqueado', function(){
    return "permite acessos definidos";
});

Route::match(['put', 'delete'],'/bloquead', function(){
    return "permite acessos definidos";
});

// Route::get('/produto/{id}', function ($id) {
//     return "o id do produto é: ". $id;
// });

Route::get('/produto/{id?}/{nome?}', function(?string $id='', ?string $nome=""){
    return "o id do produto é: ". $id. "<br>" ."o meu nome é" . $nome;
});

Route::redirect('/z', '/produto/100');

//direcionar rotas
Route::get('/sobre', function () {
    return redirect('/empresa');
});

Route::get('/sobre', function () {
    return redirect('/sla');
});

Route::redirect('/sobre', '/empresa');

// criando nome 
Route::get ('/news', function() {
    return view ('news');
})->name('noticias');

Route::get('/novidades', function () {
    return redirect()->route('noticias');
});

Route::prefix('sla')->group(function(){
    Route::get("/novidades", function(){
        return "A";

    });
    Route::get("/c", function(){
        return "B";

    });

});

Route::get('/thenews', function () {
    return view ('news');
})->name('noticias');