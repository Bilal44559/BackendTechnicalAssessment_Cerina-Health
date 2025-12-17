<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Observers\AppointmentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // AppServiceProvider.php
        Appointment::observe(AppointmentObserver::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Appointment::observe(AppointmentObserver::class);
    }
}
