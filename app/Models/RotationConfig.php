<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'rotation_type',
        'interval_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'interval_minutes' => 'integer',
    ];

    /**
     * Scope a query to only include active configurations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the current active rotation configuration.
     */
    public static function current(): ?self
    {
        return static::active()->first();
    }

    /**
     * Get available rotation types.
     */
    public static function rotationTypes(): array
    {
        return [
            'circular' => 'Rotação Circular (Round-robin)',
            'priority' => 'Baseado em Prioridade',
            'scheduled' => 'Baseado em Agendamento',
            'random' => 'Aleatório Ponderado',
        ];
    }
}
