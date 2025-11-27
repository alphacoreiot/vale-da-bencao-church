<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Geracao extends Model
{
    use HasFactory;

    protected $table = 'geracoes';

    protected $fillable = [
        'nome',
        'cor',
        'responsaveis',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Células desta geração
     */
    public function celulas(): HasMany
    {
        return $this->hasMany(Celula::class, 'geracao_id');
    }

    /**
     * Células ativas desta geração
     */
    public function celulasAtivas(): HasMany
    {
        return $this->hasMany(Celula::class, 'geracao_id')->where('ativo', true);
    }

    /**
     * Extrai a cor do nome da geração
     */
    public function extrairCor(): ?string
    {
        // Remove "Geração " do início
        $nome = preg_replace('/^Gera[çc][ãa]o\s+/i', '', $this->nome);
        return trim($nome) ?: null;
    }
}
