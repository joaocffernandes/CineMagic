<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('users/showcase', [UserController::class, 'showCase'])->name('users.showcase');
Route::delete('users/{user}/image', [UserController::class, 'destroyImage'])->name('users.image.destroy');
Route::resource('users', UserController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.destroy.photo');
});

require __DIR__.'/auth.php';

Route::get('movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('movies/create', [MovieController::class, 'create'])->name('movies.create');
Route::post('movies', [MovieController::class, 'store'])->name('movies.store');
Route::get('movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
Route::get('movies/{movie}/edit', [MovieController::class, 'edit'])->name('movies.edit');
Route::put('movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
Route::delete('movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');
