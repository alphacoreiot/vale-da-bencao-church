<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// AI Chat API
Route::prefix('ai')->group(function () {
    Route::post('/chat/{sectionSlug}', [AIController::class, 'chat'])->name('api.ai.chat');
    Route::post('/clear/{sectionSlug}', [AIController::class, 'clearConversation'])->name('api.ai.clear');
    Route::post('/history/{sectionSlug}', [AIController::class, 'getConversation'])->name('api.ai.history');
});
