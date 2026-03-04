<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = \App\Models\Produto::all();
        return view('produtos.index', compact('produtos'));
    }
}
