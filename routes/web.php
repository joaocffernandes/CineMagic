<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CartController;
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

Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
Route::get('tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
Route::put('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
Route::delete('tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
Route::post('tickets/add-to-cart', [TicketController::class, 'addToCart'])->name('tickets.add-to-cart');
Route::get('tockets/checkout', [TicketController::class, 'checkout'])->name('tickets.checkout');


Route::post('cart/{ticket}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('cart/{ticket}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('cart', [CartController::class, 'show'])->name('cart.show');
Route::post('cart', [CartController::class, 'confirm'])->name('cart.confirm');
Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');
