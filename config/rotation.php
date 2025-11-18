<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Section Rotation Configuration
    |--------------------------------------------------------------------------
    */

    /*
    | Enable or disable automatic rotation
    */
    'enabled' => env('ROTATION_ENABLED', true),

    /*
    | Default rotation interval in minutes
    */
    'interval' => env('ROTATION_INTERVAL', 60),

    /*
    | Default rotation type
    | Options: circular, priority, scheduled, random
    */
    'default_type' => env('ROTATION_TYPE', 'priority'),

    /*
    | Minimum highlight duration in minutes
    */
    'min_duration' => 15,

    /*
    | Maximum highlight duration in minutes
    */
    'max_duration' => 1440, // 24 hours

    /*
    | Log rotation activities
    */
    'log_rotations' => env('ROTATION_LOG', true),
];
