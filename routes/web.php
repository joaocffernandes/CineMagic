<?php 

use App\Http\Controllers\GenreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SeatController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatsController;

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

Route::middleware('auth', 'verified', 'can:admin')->group(function () {
    Route::get('movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('movies/create', [MovieController::class, 'create'])->name('movies.create');
    Route::post('movies', [MovieController::class, 'store'])->name('movies.store');
    Route::get('movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
    Route::get('movies/{movie}/edit', [MovieController::class, 'edit'])->name('movies.edit');
    Route::put('movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
    Route::delete('movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');
    Route::delete('movies/{movie}/poster', [MovieController::class, 'destroyPoster'])->name('movies.destroy.poster');

    Route::get('genres', [GenreController::class, 'index'])->name('genres.index');
    Route::get('genres/create', [GenreController::class, 'create'])->name('genres.create');
    Route::post('genres', [GenreController::class, 'store'])->name('genres.store');
    Route::get('genres/{genre}/edit', [GenreController::class, 'edit'])->name('genres.edit');
    Route::put('genres/{genre}', [GenreController::class, 'update'])->name('genres.update');
    Route::delete('genre/{genre}', [GenreController::class, 'destroy'])->name('genres.destroy');

    Route::get('theaters', [TheaterController::class, 'index'])->name('theaters.index');
    Route::get('theaters/create', [TheaterController::class, 'create'])->name('theaters.create');
    Route::post('theaters', [TheaterController::class, 'store'])->name('theaters.store');
    Route::get('theaters/{theater}', [TheaterController::class, 'show'])->name('theaters.show');
    Route::get('theaters/{theater}/edit', [TheaterController::class, 'edit'])->name('theaters.edit');
    Route::put('theaters/{theater}', [TheaterController::class, 'update'])->name('theaters.update');
    Route::delete('theaters/{theater}', [TheaterController::class, 'destroy'])->name('theaters.destroy');

    Route::get('screenings/{screening}', [MovieController::class, 'editScreening'])->name('screenings.edit');
    Route::put('screenings/{screening}/update', [MovieController::class, 'updateScreening'])->name('screenings.update');
    Route::delete('screenings/{screening}', [MovieController::class, 'destroyScreening'])->name('screenings.destroy');
    Route::get('screenings/create/{movie}', [MovieController::class, 'createScreening'])->name('screenings.create');
    Route::post('screenings/create/{movie}', [MovieController::class, 'storeScreening'])->name('screenings.store');

    Route::get('configuration', [MovieController::class, 'editPrice'])->name('configuration.edit');
    Route::put('configuration', [MovieController::class, 'updatePrice'])->name('configuration.update');

    Route::delete('users/{user}/image', [UserController::class, 'destroyImage'])->name('users.image.destroy');
    Route::get('customers', [UserController::class, 'customers'])->name('customers');
    Route::put('users/{user}/block', [UserController::class, 'block'])->name('users.block');
    Route::delete('users/{user}/photo', [UserController::class, 'destroyPhoto'])->name('users.destroy.photo');
    Route::resource('users', UserController::class);

    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
});

Route::middleware('auth', 'verified', 'can:employee')->group(function () {
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/create/', [TicketController::class, 'create'])->name('tickets.create');
    Route::get('tickets/{ticket}', [TicketController::class, 'showByTicket'])->name('tickets.show');
    Route::get('tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::get('tickets/{ticket}/receipt/download', [TicketController::class, 'downloadReceipt'])->name('tickets.receipt.download');
    Route::get('tickets/{ticket}/receipt/show', [TicketController::class, 'showReceipt'])->name('tickets.receipt.show'); // For test

    Route::get('purchase/', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchase/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('purchase/resend-email/{purchase}', [PurchaseController::class, 'resendEmail'])->name('purchases.resend_email');
    Route::get('purchase/{purchase}/receipt/download', [PurchaseController::class, 'downloadReceipt'])->name('purchases.receipt.download');
});

Route::get('seats/reserve/{screening}/{quantTickets}', [SeatController::class, 'show'])->name('seats.reserve');

Route::post('cart/createTicketAndAddToCart', [CartController::class, 'createTicketAndAddToCart'])->name('cart.createTicketAndAddToCart');
Route::delete('cart/{screeningId}/{seatId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('cart', [CartController::class, 'show'])->name('cart.show');
Route::post('cart', [CartController::class, 'confirm'])->name('cart.confirm');
Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

Route::get('tickets/{screeningId}/{seatId}', [TicketController::class, 'showBySession'])->name('tickets.showBySession');
Route::get('screenings', [MovieController::class, 'screenings'])->name('screenings.index');

