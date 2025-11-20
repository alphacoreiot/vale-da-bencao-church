<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devocional extends Model
{
    protected $table = 'devocionais';
    
    protected $fillable = [
        'titulo',
        'descricao',
        'texto',
        'imagem',
        'data',
        'ativo',
    ];

    protected $casts = [
        'data' => 'date',
        'ativo' => 'boolean',
    ];

    /**
     * Scope para buscar devocional ativo do dia
     */
    public function scopeAtivoDoDia($query)
    {
        return $query->where('ativo', true)
                    ->whereDate('data', today())
                    ->latest();
    }

    /**
     * Scope para buscar devocional ativo mais recente
     */
    public function scopeAtivoRecente($query)
    {
        return $query->where('ativo', true)
                    ->latest('data');
    }
}
