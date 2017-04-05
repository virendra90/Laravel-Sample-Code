<?php

namespace App\Providers;

use App\Models\CommonAllergy;
use App\Models\CommonIllness;
use App\Models\User;

use App\Policies\CommonAllergyPolicy;
use App\Policies\CommonIllnessPolicy;
use App\Policies\UserPolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        CommonAllergy::class => CommonAllergyPolicy::class,
        CommonIllness::class => CommonIllnessPolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
