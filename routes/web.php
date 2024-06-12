<?php

use App\Http\Controllers\GenreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TheaterController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit')
        ->middleware('can:crudMy,App\Models\User');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update')
        ->middleware('can:crudMy,App\Models\User');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy')
        ->middleware('can:crudMy,App\Models\User');;
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])
        ->name('profile.destroy.photo')
        ->middleware('can:crudMy,App\Models\User');;
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('movies/create', [MovieController::class, 'create'])->name('movies.create');
    Route::post('movies', [MovieController::class, 'store'])->name('movies.store');
    Route::get('movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
    Route::get('movies/{movie}/edit', [MovieController::class, 'edit'])->name('movies.edit');
    Route::put('movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
    Route::delete('movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('genres', [GenreController::class, 'index'])->name('genres.index');
    Route::get('genres/create', [GenreController::class, 'create'])->name('genres.create');
    Route::post('genres', [GenreController::class, 'store'])->name('genres.store');
    Route::get('genres/{genre}/edit', [GenreController::class, 'edit'])->name('genres.edit');
    Route::put('genres/{genre}', [GenreController::class, 'update'])->name('genres.update');
    Route::delete('genre/{genre}', [GenreController::class, 'destroy'])->name('genres.destroy');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('theaters', [TheaterController::class, 'index'])->name('theaters.index');
    Route::get('theaters/create', [TheaterController::class, 'create'])->name('theaters.create');
    Route::post('theaters', [TheaterController::class, 'store'])->name('theaters.store');
    Route::get('theaters/{theater}', [TheaterController::class, 'show'])->name('theaters.show');
    Route::get('theaters/{theater}/edit', [TheaterController::class, 'edit'])->name('theaters.edit');
    Route::put('theaters/{theater}', [TheaterController::class, 'update'])->name('theaters.update');
    Route::delete('theaters/{theater}', [TheaterController::class, 'destroy'])->name('theaters.destroy');
});