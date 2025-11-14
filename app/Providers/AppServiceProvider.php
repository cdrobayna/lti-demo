<?php

namespace App\Providers;

use App\Services\Lti\Lti13Cache;
use App\Services\Lti\Lti13Cookie;
use App\Services\Lti\Lti13Database;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Packback\Lti1p3\Interfaces\ICache;
use Packback\Lti1p3\Interfaces\ICookie;
use Packback\Lti1p3\Interfaces\IDatabase;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Packback\Lti1p3\LtiServiceConnector;

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
        if (env('APP_URL')) {
            URL::forceScheme('https'); // fuerza https en helpers asset()
        }

        // Set JWT leeway to 5 seconds
        JWT::$leeway = 5;

        // Register LTI interfaces
        $this->app->bind(ICache::class, Lti13Cache::class);
        $this->app->bind(ICookie::class, Lti13Cookie::class);
        $this->app->bind(IDatabase::class, Lti13Database::class);
        $this->app->bind(ILtiServiceConnector::class, function ($app) {
            $client = new Client;
            $cache = $app->make(ICache::class);

            return (new LtiServiceConnector($cache, $client))
                ->setDebuggingMode(config('app.debug'));
        });
    }
}
