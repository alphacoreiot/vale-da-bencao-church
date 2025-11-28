<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para células cadastradas via formulário
 * Tabela: form_celulas_recadastramento
 */
class CelulaCadastro extends Model
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
        'ponto_referencia',
        'contato',
        'contato2_nome',
        'contato2_whatsapp',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Geração a qual pertence esta célula
     */
    public function geracao(): BelongsTo
    {
        return $this->belongsTo(Geracao::class, 'geracao_id');
    }

    /**
     * Scope para células aprovadas
     */
    public function scopeAprovadas($query)
    {
        return $query->where('status', 'aprovado');
    }

    /**
     * Retorna o endereço completo formatado
     */
    public function getEnderecoCompletoAttribute(): string
    {
        $partes = [];
        
        if ($this->rua) {
            $partes[] = $this->rua;
        }
        if ($this->numero) {
            $partes[] = $this->numero;
        }
        if ($this->complemento) {
            $partes[] = $this->complemento;
        }
        
        return implode(', ', $partes) ?: $this->bairro;
    }

    /**
     * Formata o contato para link do WhatsApp
     */
    public function getWhatsappLinkAttribute(): ?string
    {
        if (!$this->contato) {
            return null;
        }

        // Remove caracteres não numéricos
        $numero = preg_replace('/\D/', '', $this->contato);
        
        // Adiciona código do país se não tiver
        if (strlen($numero) <= 11) {
            $numero = '55' . $numero;
        }

        return "https://wa.me/{$numero}";
    }

    /**
     * Verifica se tem coordenadas válidas
     */
    public function getTemCoordenadasAttribute(): bool
    {
        return !empty($this->latitude) && !empty($this->longitude);
    }
}
