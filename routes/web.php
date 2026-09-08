<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('home');

    Route::resource('projects', ProjectController::class)
        ->middlewareFor(
            ['show', 'edit', 'update', 'destroy'],
            'project.owner'
        );

    Route::resource('projects.tasks', TaskController::class)
        ->except(['index'])
        ->scoped()
        ->middleware('project.owner');

    Route::resource('tags', TagController::class)->except(['show']);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [
        AuthenticatedSessionController::class,
        'create',
    ])->name('login');

    Route::post('/login', [
        AuthenticatedSessionController::class,
        'store',
    ])->middleware('throttle:5,1');
});

Route::post('/logout', [
    AuthenticatedSessionController::class,
    'destroy',
])->middleware('auth')->name('logout');
