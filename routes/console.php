<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\SectionRotationService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Section Rotation Scheduler
Schedule::call(function () {
    $rotationService = app(SectionRotationService::class);
    $rotationService->rotate();
})->hourly()
  ->when(config('rotation.enabled', true))
  ->name('rotate-sections')
  ->description('Rotate highlighted sections automatically');

// Alternative: Using a custom command (you can create this later)
// Schedule::command('sections:rotate')->hourly()->when(config('rotation.enabled'));

