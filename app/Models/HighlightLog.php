<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HighlightLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'started_at',
        'ended_at',
        'reason',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the section that owns the log.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Scope a query to only include active highlights.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('ended_at')
            ->where('started_at', '<=', now());
    }

    /**
     * Get the duration of the highlight in minutes.
     */
    public function getDurationMinutes(): ?int
    {
        if (!$this->ended_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->ended_at);
    }

    /**
     * Check if the highlight is currently active.
     */
    public function isActive(): bool
    {
        return $this->ended_at === null && $this->started_at <= now();
    }

    /**
     * End the highlight.
     */
    public function end(?string $reason = null): bool
    {
        $this->ended_at = now();
        if ($reason) {
            $this->reason = $reason;
        }
        return $this->save();
    }
}
