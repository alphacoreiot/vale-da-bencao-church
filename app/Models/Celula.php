<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Celula extends Model
{
    use HasFactory;

    protected $table = 'celulas';

    protected $fillable = [
        'geracao_id',
        'nome',
        'lider',
        'bairro',
        'ponto_referencia',
        'contato',
        'dia_semana',
        'horario',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'horario' => 'datetime:H:i',
    ];

    /**
     * Geração a qual pertence esta célula
     */
    public function geracao(): BelongsTo
    {
        return $this->belongsTo(Geracao::class, 'geracao_id');
    }

    /**
     * Formata o contato para exibição
     */
    public function getContatoFormatadoAttribute(): ?string
    {
        if (!$this->contato) {
            return null;
        }

        // Remove caracteres não numéricos
        $numeros = preg_replace('/[^0-9\/]/', '', $this->contato);
        
        // Se tem múltiplos contatos separados por /
        if (str_contains($this->contato, '/')) {
            return $this->contato;
        }

        // Formata telefone brasileiro
        if (strlen($numeros) === 11) {
            return sprintf('(%s) %s-%s', 
                substr($numeros, 0, 2), 
                substr($numeros, 2, 5), 
                substr($numeros, 7)
            );
        }

        return $this->contato;
    }

    /**
     * Gera link do WhatsApp
     */
    public function getWhatsappLinkAttribute(): ?string
    {
        if (!$this->contato) {
            return null;
        }

        // Pega o primeiro número se houver múltiplos
        $contato = explode('/', $this->contato)[0];
        $numeros = preg_replace('/[^0-9]/', '', $contato);

        // Adiciona código do Brasil se necessário
        if (strlen($numeros) === 11) {
            $numeros = '55' . $numeros;
        }

        return "https://wa.me/{$numeros}";
    }
}
