<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Tujuan redirect setelah login.
     * Dipakai oleh middleware RedirectIfAuthenticated (guest).
     */
    public const HOME = '/costing/input';

    public function register(): void {}

    public function boot(): void {}
}