<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
        Gate::policy(\Spatie\Permission\Models\Permission::class, \App\Policies\PermissionPolicy::class);
        Gate::policy(\Spatie\Permission\Models\Role::class, \App\Policies\RolePolicy::class);
    }
}
