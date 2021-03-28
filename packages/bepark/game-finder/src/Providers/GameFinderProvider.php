<?php

namespace BePark\GameFinder\Providers;

use BePark\GameFinder\Clients\IgdbClient;
use BePark\GameFinder\Clients\RawgClient;
use BePark\GameFinder\GameFinder;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class GameFinderProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            GameFinder::class,
            function (Application $app) {
                $clients = [
                    $app->make(RawgClient::class),
                    $app->make(IgdbClient::class),
                ];

                $finder = new GameFinder();
                foreach ($clients as $client) {
                    $finder->addClient($client);
                }

                return $finder;
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
