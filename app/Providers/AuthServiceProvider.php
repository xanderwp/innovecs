<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Bridge\PersonalAccessGrant;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessClient;
use League\OAuth2\Server\AuthorizationServer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

        $this->app->get(AuthorizationServer::class)
            ->enableGrantType(new PersonalAccessGrant(), new \DateInterval('P1W'));

        Passport::tokensCan([
            'api' => 'api for transactions'
        ]);

        Passport::tokensExpireIn(Carbon::now()->addDays(1));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(1));
    }
}
