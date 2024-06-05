<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Genre;
use App\Models\User;

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
        Gate::define('admin', function (User $user) {
            // Only "administrator" users can "admin"
            return $user->type;
        });
    }
}
