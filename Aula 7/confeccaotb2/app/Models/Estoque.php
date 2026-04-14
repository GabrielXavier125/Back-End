<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $guarded = [];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
