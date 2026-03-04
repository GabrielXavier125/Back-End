<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function index()
    {
        $estoques = \App\Models\Estoque::with('produto')->get();
        return view('estoques.index', compact('estoques'));
    }
}
