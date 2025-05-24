<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;  // import Gate facade

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Product::class => \App\Policies\ProductPolicy::class,
        // Add other model => policy mappings here
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define a gate named 'accessAdmin' (or whatever you want)
        Gate::define('accessAdmin', function ($user) {
            return $user->hasAnyRole(['admin', 'vendor']);  // Adjust this check based on how you store user roles
             // Only admins can pass
            // Or for admins + vendors:
            // return in_array($user->role, ['admin', 'vendor']);
        });
    }
}

