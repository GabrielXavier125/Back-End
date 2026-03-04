<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'produto_id', 'quantidade', 'total', 'status'];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
