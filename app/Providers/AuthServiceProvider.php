<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Import your models and policies
use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Models\ProductVariationSet;
use App\Policies\ProductVariationSetPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        ProductVariationSet::class => ProductVariationSetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Optional gate (e.g., for restricting admin panel access)
        Gate::define('accessAdmin', function ($user) {
            return $user->hasAnyRole(['admin', 'vendor']);
        });
    }
}


