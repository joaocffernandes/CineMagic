<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Genre;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View::share adds data (variables) that are shared through all views
        $allGenres = Genre::all();
        $options = $allGenres->pluck('name', 'code')->toArray();       
        View::share('genres', $options);
    }
}
