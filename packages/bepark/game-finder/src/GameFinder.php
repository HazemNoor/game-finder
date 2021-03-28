<?php

namespace BePark\GameFinder;

use BePark\GameFinder\Clients\AbstractClient;
use BePark\GameFinder\Clients\IgdbClient;
use BePark\GameFinder\Clients\RawgClient;
use DateInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GameFinder
{
    /**
     * Result limit per client
     * todo: Move to config
     */
    private const RESULT_LIMIT = 10;

    /**
     * Cache result lifetime
     * todo: Move to config
     */
    private const CACHE_LIFETIME = '1 hour';

    /**
     * @var AbstractClient[]
     */
    private array $clients = [];

    public function addClient(AbstractClient $client)
    {
        $this->clients[] = $client;
    }

    public function search(string $searchQuery, bool $cacheResults = true): array
    {
        $closure = function () use ($searchQuery) {
            $results = new Collection();

            foreach ($this->clients as $client) {
                $results = $results->merge($client->search($searchQuery, self::RESULT_LIMIT));
            }

            // Unique results by name
            return array_values(
                $results->unique(
                    function (GameResult $result) {
                        return $result->getName();
                    }
                )->all()
            );
        };

        if ($cacheResults) {
            return Cache::remember(
                $searchQuery,
                DateInterval::createFromDateString(self::CACHE_LIFETIME),
                $closure
            );
        } else {
            return $closure();
        }
    }
}
