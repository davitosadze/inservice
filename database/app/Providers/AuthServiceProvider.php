<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Report;
use App\Policies\ReportPolicy;

use App\Models\Category;
use App\Policies\CategoryPolicy;

use App\Models\Invoice;
use App\Policies\InvoicePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Invoice' => 'App\Policies\InvoicePolicy',
        'App\Models\Report' => 'App\Policies\ReportPolicy',
        'App\Models\Category' => 'App\Policies\CategoryPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        
        Gate::after(function ($user, $ability) {
            return $user->hasRole('director') ? true : false;
        });

        Gate::define('isInter', function (User $user) {
            return $user->hasRole('director') || $user->hasRole('დირექტორი');
        });
    }
}
