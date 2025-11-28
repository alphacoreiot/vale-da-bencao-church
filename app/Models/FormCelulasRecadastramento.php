<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormCelulasRecadastramento extends Model
{
    protected $table = 'form_celulas_recadastramento';

    protected $fillable = [
        'nome_celula',
        'lider',
        'geracao_id',
        'bairro',
        'rua',
        'numero',
        'complemento',
        'contato',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function geracao(): BelongsTo
    {
        return $this->belongsTo(Geracao::class);
    }
}
