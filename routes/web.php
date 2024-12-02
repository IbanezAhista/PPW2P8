<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SendEmailController;

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/buku', [BookController::class, 'index'])->name('buku');
Route::get('/buku/create', [BookController::class, 'create']) -> name('buku.create');
Route::post('/buku', [BookController::class, 'store']) -> name('buku.store');
Route::delete('/buku/{id}', [BookController::class, 'destroy']) -> name('buku.destroy');
Route::get('/buku/edit/{id}', [BookController::class, 'edit']) -> name('buku.edit');
Route::post('/buku/update/{id}', [BookController::class, 'update']) -> name('buku.update');

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register')->middleware('guest');
    Route::post('/store', 'store')->name('store')->middleware('guest');
    Route::get('/login', 'login')->name('login')->middleware('guest');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard')->middleware(['auth', 'admin']);
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
    Route::get('/restricted', function() {
        return "Anda merupakan Admin!";
    })->name('txtAdmin');
});

Route::get('/users', [UserController::class, 'index'])->name('users');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update');

Route::resource('gallery', GalleryController::class);
Route::get('/create', [GalleryController::class, 'create'])->name('gallery.create');
Route::get('/edit/{id}', [GalleryController::class, 'edit'])->name('gallery.edit');
Route::post('/upadate/{id}', [GalleryController::class, 'update'])->name('gallery.update');
Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

Route::get('/send-email', [SendEmailController::class, 'index'])->name('kirim-email');
Route::post('/post-email', [SendEmailController::class, 'store'])->name('post-email');
Route::get('/send-email/send/{email}/{name}', [SendEmailController::class, 'send'])->name('send-email.send');

