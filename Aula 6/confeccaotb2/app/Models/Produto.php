<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $guarded = [];

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }
}
