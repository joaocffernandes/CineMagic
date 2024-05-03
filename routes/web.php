<?php

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('movies/create', [MovieController::class, 'create'])->name('movies.create');
