<?php

use App\Http\Controllers\GenerationController;
use App\Http\Controllers\RuntimeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RuntimeController::class, 'index'])->name('index');
Route::get('/generate', [GenerationController::class, 'index']);
Route::post('/generate', [GenerationController::class, 'index'])->name('generate');
