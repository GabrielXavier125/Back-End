<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        // Restaura estoque apenas se o pedido estava Concluído
        static::deleting(function (Pedido $pedido) {
            if ($pedido->status === 'concluido') {
                Estoque::where('produto_id', $pedido->produto_id)
                    ->increment('quantidade', $pedido->quantidade);
            }
        });
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function itens()
    {
        return $this->hasMany(ItemPedido::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
