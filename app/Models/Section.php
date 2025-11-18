<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'priority',
        'display_order',
        'highlight_duration',
        'last_highlighted_at',
        'next_highlight_at',
        'ai_agent_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'display_order' => 'integer',
        'highlight_duration' => 'integer',
        'last_highlighted_at' => 'datetime',
        'next_highlight_at' => 'datetime',
        'ai_agent_config' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($section) {
            if (empty($section->slug)) {
                $section->slug = Str::slug($section->name);
            }
        });
    }

    /**
     * Get the contents for the section.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(SectionContent::class);
    }

    /**
     * Get the published contents for the section.
     */
    public function publishedContents(): HasMany
    {
        return $this->hasMany(SectionContent::class)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc');
    }

    /**
     * Get the media for the section.
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Get the highlight logs for the section.
     */
    public function highlightLogs(): HasMany
    {
        return $this->hasMany(HighlightLog::class);
    }

    /**
     * Get the AI conversations for the section.
     */
    public function aiConversations(): HasMany
    {
        return $this->hasMany(AIConversation::class);
    }

    /**
     * Scope a query to only include active sections.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order sections by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    /**
     * Check if the section is currently highlighted.
     */
    public function isHighlighted(): bool
    {
        if (!$this->next_highlight_at) {
            return false;
        }

        return now()->between(
            $this->last_highlighted_at,
            $this->next_highlight_at
        );
    }

    /**
     * Get the AI agent configuration with defaults.
     */
    public function getAiAgentConfig(): array
    {
        return array_merge([
            'enabled' => false,
            'name' => 'Assistente',
            'personality' => 'amigável e prestativo',
            'knowledge_base' => [],
            'capabilities' => [],
            'prompts' => [
                'system' => 'Você é um assistente da igreja.',
                'context' => 'Use apenas informações do sistema.',
            ],
        ], $this->ai_agent_config ?? []);
    }
}
