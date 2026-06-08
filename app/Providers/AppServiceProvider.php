<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Policies\AssignmentPolicy;
use App\Policies\ClassRoomPolicy;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(ClassRoom::class, ClassRoomPolicy::class);
        Gate::policy(Assignment::class, AssignmentPolicy::class);
    }
}