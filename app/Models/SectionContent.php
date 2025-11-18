<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'content',
        'type',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the section that owns the content.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the media for the content.
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'content_id');
    }

    /**
     * Scope a query to only include published contents.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Publish the content.
     */
    public function publish(): bool
    {
        $this->is_published = true;
        $this->published_at = $this->published_at ?? now();
        return $this->save();
    }

    /**
     * Unpublish the content.
     */
    public function unpublish(): bool
    {
        $this->is_published = false;
        return $this->save();
    }
}
