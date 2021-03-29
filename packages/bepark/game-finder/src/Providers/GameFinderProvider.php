<?php

namespace BePark\GameFinder\Providers;

use BePark\GameFinder\Clients\IgdbClient;
use BePark\GameFinder\Clients\RawgClient;
use BePark\GameFinder\Factories\ClientFactory;
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
            ClientFactory::class,
            function (Application $app) {
                $clients = [
                    $app->make(RawgClient::class),
                    $app->make(IgdbClient::class),
                ];

                $factory = new ClientFactory();
                foreach ($clients as $client) {
                    $factory->addClient($client);
                }

                return $factory;
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
