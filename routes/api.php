<?php

use App\Http\Controllers\GalleryController;
use App\Http\Controllers\InfoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GreetController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/info', [InfoController::class, 'index'])->name('info');

Route::get('/greet', [GreetController::class, 'greet'])->name('greet');

Route::post('gallery', [GalleryController::class, 'gallery'])->name('gallery');
