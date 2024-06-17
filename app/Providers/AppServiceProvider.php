<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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
        // Definir uma autorização de 'admin'
        Gate::define('admin', function (User $user) {
            // Apenas usuários do tipo "A" podem acessar recursos de administrador
            return $user->type == 'A';
        });

        Gate::define('employee', function (User $user) {
            // Apenas usuários do tipo "A" podem acessar recursos de administrador
            return $user->type != 'E';
        });
    }
}
