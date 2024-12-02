<?php

use App\Http\Controllers\GalleryController;
use App\Http\Controllers\InfoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GreetController;
use App\Http\Controllers\UserController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/info', [InfoController::class, 'index'])->name('info');

Route::get('/greet', [GreetController::class, 'greet'])->name('greet');

Route::post('gallery', [GalleryController::class, 'gallery'])->name('gallery');

Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');

Route::get('/users', [UserController::class, 'index'])->name('users');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update');
