<?php

namespace App\Providers;

use App\Guards\CustomAuthGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // add custom guard provider
        Auth::provider('access_token', function ($app, array $config) {
            return new CustomUserProvider($app->make('App\Models\User'));
        });

        // add custom guard
        Auth::extend('access_token', function ($app, $name, array $config) {
            return new CustomAuthGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
        });
        //
    }
}
