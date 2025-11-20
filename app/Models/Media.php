<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'content_id',
        'type',
        'path',
        'thumbnail',
        'size',
        'mime_type',
        'alt_text',
        'order',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Get the section that owns the media.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the content that owns the media.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(SectionContent::class, 'content_id');
    }

    /**
     * Get the full URL for the media.
     */
    public function getUrl(): string
    {
        return Storage::url($this->path);
    }

    /**
     * Get the full URL for the thumbnail.
     */
    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnail ? Storage::url($this->thumbnail) : null;
    }

    /**
     * Get human-readable file size.
     */
    public function getHumanSize(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Check if media is an image.
     */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Check if media is a video.
     */
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /**
     * Check if media is audio.
     */
    public function isAudio(): bool
    {
        return $this->type === 'audio';
    }
}
