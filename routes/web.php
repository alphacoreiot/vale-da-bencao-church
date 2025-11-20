<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SectionController;
use App\Http\Controllers\Admin\AuthController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/secao/{slug}', [SectionController::class, 'show'])->name('section.show');
Route::get('/secao/{sectionSlug}/conteudo/{contentId}', [SectionController::class, 'content'])->name('section.content');

// Admin Auth Routes (public)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Routes (protected by auth middleware)
Route::prefix('admin')->name('admin.')->middleware(['admin.auth', 'log.activity'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // User Groups
    Route::resource('user-groups', App\Http\Controllers\Admin\UserGroupController::class);
    
    // Users
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggle'])->name('users.toggle');
    
    // Logs
    Route::get('logs', [App\Http\Controllers\Admin\UserLogController::class, 'index'])->name('logs.index');
    
    // Content Manager
    Route::get('content', [App\Http\Controllers\Admin\ContentManagerController::class, 'index'])->name('content.index');
    Route::get('content/eventos', [App\Http\Controllers\Admin\ContentManagerController::class, 'eventos'])->name('content.eventos');
    Route::post('content/eventos', [App\Http\Controllers\Admin\ContentManagerController::class, 'eventosStore'])->name('content.eventos.store');
    Route::post('content/eventos/reorder', [App\Http\Controllers\Admin\ContentManagerController::class, 'eventosReorder'])->name('content.eventos.reorder');
    Route::delete('content/eventos/{media}', [App\Http\Controllers\Admin\ContentManagerController::class, 'eventosDestroy'])->name('content.eventos.destroy');
    
    // Sections
    Route::resource('sections', App\Http\Controllers\Admin\SectionController::class);
    Route::post('sections/{section}/toggle', [App\Http\Controllers\Admin\SectionController::class, 'toggle'])->name('sections.toggle');
    Route::post('sections/{section}/highlight', [App\Http\Controllers\Admin\SectionController::class, 'highlight'])->name('sections.highlight');
    
    // Contents (nested under sections)
    Route::prefix('sections/{section}')->name('contents.')->group(function () {
        Route::get('contents', [App\Http\Controllers\Admin\ContentController::class, 'index'])->name('index');
        Route::get('contents/create', [App\Http\Controllers\Admin\ContentController::class, 'create'])->name('create');
        Route::post('contents', [App\Http\Controllers\Admin\ContentController::class, 'store'])->name('store');
        Route::get('contents/{content}', [App\Http\Controllers\Admin\ContentController::class, 'show'])->name('show');
        Route::get('contents/{content}/edit', [App\Http\Controllers\Admin\ContentController::class, 'edit'])->name('edit');
        Route::put('contents/{content}', [App\Http\Controllers\Admin\ContentController::class, 'update'])->name('update');
        Route::delete('contents/{content}', [App\Http\Controllers\Admin\ContentController::class, 'destroy'])->name('destroy');
        Route::post('contents/{content}/publish', [App\Http\Controllers\Admin\ContentController::class, 'publish'])->name('publish');
        Route::post('contents/{content}/unpublish', [App\Http\Controllers\Admin\ContentController::class, 'unpublish'])->name('unpublish');
    });
    
    // Media (nested under sections)
    Route::prefix('sections/{section}')->name('media.')->group(function () {
        Route::get('media', [App\Http\Controllers\Admin\MediaController::class, 'index'])->name('index');
        Route::get('media/create', [App\Http\Controllers\Admin\MediaController::class, 'create'])->name('create');
        Route::post('media', [App\Http\Controllers\Admin\MediaController::class, 'store'])->name('store');
        Route::get('media/{media}/edit', [App\Http\Controllers\Admin\MediaController::class, 'edit'])->name('edit');
        Route::put('media/{media}', [App\Http\Controllers\Admin\MediaController::class, 'update'])->name('update');
        Route::delete('media/{media}', [App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('destroy');
        Route::get('media/gallery', [App\Http\Controllers\Admin\MediaController::class, 'gallery'])->name('gallery');
    });
    
    // Rotation Configuration
    Route::prefix('rotation')->name('rotation.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\RotationController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\Admin\RotationController::class, 'update'])->name('update');
        Route::post('/rotate', [App\Http\Controllers\Admin\RotationController::class, 'rotate'])->name('rotate');
        Route::post('/toggle', [App\Http\Controllers\Admin\RotationController::class, 'toggle'])->name('toggle');
    });
});
