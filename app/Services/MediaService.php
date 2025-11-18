<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Section;
use App\Models\SectionContent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class MediaService
{
    /**
     * Upload and store media file.
     */
    public function upload(
        UploadedFile $file,
        Section $section,
        ?SectionContent $content = null,
        ?string $altText = null
    ): Media {
        $type = $this->determineMediaType($file);
        $path = $this->storeFile($file, $type);
        $thumbnail = null;

        // Generate thumbnail for images and videos
        if ($type === 'image') {
            $thumbnail = $this->generateImageThumbnail($file);
        } elseif ($type === 'video') {
            $thumbnail = $this->generateVideoThumbnail($path);
        }

        return Media::create([
            'section_id' => $section->id,
            'content_id' => $content?->id,
            'type' => $type,
            'path' => $path,
            'thumbnail' => $thumbnail,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'alt_text' => $altText,
        ]);
    }

    /**
     * Determine media type from file.
     */
    private function determineMediaType(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();

        if (Str::startsWith($mimeType, 'image/')) {
            return 'image';
        } elseif (Str::startsWith($mimeType, 'video/')) {
            return 'video';
        } elseif (Str::startsWith($mimeType, 'audio/')) {
            return 'audio';
        }

        return 'image'; // Default
    }

    /**
     * Store file in appropriate directory.
     */
    private function storeFile(UploadedFile $file, string $type): string
    {
        $directory = match ($type) {
            'image' => 'media/images',
            'video' => 'media/videos',
            'audio' => 'media/audio',
            default => 'media/other',
        };

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Generate thumbnail for image.
     */
    private function generateImageThumbnail(UploadedFile $file): ?string
    {
        try {
            $filename = Str::uuid() . '_thumb.jpg';
            $path = 'media/thumbnails/' . $filename;
            $fullPath = storage_path('app/public/' . $path);

            // Create directory if it doesn't exist
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Create thumbnail - Check if Intervention Image is available
            if (class_exists(Image::class)) {
                Image::read($file->getRealPath())
                    ->resize(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($fullPath);
            } else {
                // Fallback: copy original as thumbnail
                copy($file->getRealPath(), $fullPath);
            }

            return $path;
        } catch (\Exception $e) {
            \Log::error('Failed to generate image thumbnail: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate thumbnail for video (placeholder for now).
     */
    private function generateVideoThumbnail(string $videoPath): ?string
    {
        // This would require FFmpeg
        // For now, return null or a default video thumbnail
        return null;
    }

    /**
     * Delete media and its files.
     */
    public function delete(Media $media): bool
    {
        // Delete the main file
        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }

        // Delete the thumbnail
        if ($media->thumbnail && Storage::disk('public')->exists($media->thumbnail)) {
            Storage::disk('public')->delete($media->thumbnail);
        }

        return $media->delete();
    }

    /**
     * Update media metadata.
     */
    public function updateMetadata(Media $media, array $data): Media
    {
        $media->update([
            'alt_text' => $data['alt_text'] ?? $media->alt_text,
        ]);

        return $media;
    }

    /**
     * Get media statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total_media' => Media::count(),
            'images' => Media::where('type', 'image')->count(),
            'videos' => Media::where('type', 'video')->count(),
            'audio' => Media::where('type', 'audio')->count(),
            'total_size' => Media::sum('size'),
        ];
    }

    /**
     * Get allowed file extensions by type.
     */
    public static function getAllowedExtensions(string $type): array
    {
        return match ($type) {
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
            'video' => ['mp4', 'avi', 'mov', 'wmv', 'webm'],
            'audio' => ['mp3', 'wav', 'ogg', 'aac', 'm4a'],
            default => [],
        };
    }

    /**
     * Get max file size by type (in KB).
     */
    public static function getMaxFileSize(string $type): int
    {
        return match ($type) {
            'image' => 10240, // 10MB
            'video' => 102400, // 100MB
            'audio' => 20480, // 20MB
            default => 5120, // 5MB
        };
    }
}
