<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Media Storage Configuration
    |--------------------------------------------------------------------------
    */

    /*
    | Default storage disk for media
    */
    'disk' => env('MEDIA_DISK', 'public'),

    /*
    | Maximum file sizes in KB
    */
    'max_sizes' => [
        'image' => env('MEDIA_MAX_IMAGE_SIZE', 10240), // 10MB
        'video' => env('MEDIA_MAX_VIDEO_SIZE', 102400), // 100MB
        'audio' => env('MEDIA_MAX_AUDIO_SIZE', 20480), // 20MB
    ],

    /*
    | Allowed file extensions
    */
    'allowed_extensions' => [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
        'video' => ['mp4', 'avi', 'mov', 'wmv', 'webm'],
        'audio' => ['mp3', 'wav', 'ogg', 'aac', 'm4a'],
    ],

    /*
    | Thumbnail settings
    */
    'thumbnail' => [
        'width' => 300,
        'height' => 300,
        'quality' => 80,
    ],

    /*
    | Image optimization
    */
    'optimize_images' => env('MEDIA_OPTIMIZE_IMAGES', true),

    /*
    | Video processing (requires FFmpeg)
    */
    'ffmpeg_enabled' => env('MEDIA_FFMPEG_ENABLED', false),
    'ffmpeg_path' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
];
