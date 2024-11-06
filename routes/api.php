<?php

use App\Http\Controllers\GenerationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/checkpoints', [GenerationController::class, 'getCheckpoint']);
Route::post('/loadcheckpoint', [GenerationController::class, 'loadCheckpoint']);
Route::post('/addcheckpoint', [GenerationController::class, 'addCheckpoint']);