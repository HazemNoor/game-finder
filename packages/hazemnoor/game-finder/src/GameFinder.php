<?php

namespace HazemNoor\GameFinder;

use HazemNoor\GameFinder\Factories\ClientFactory;
use DateInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GameFinder
{
    /**
     * Result limit per client
     */
    private const RESULT_LIMIT = 10;

    /**
     * Cache result lifetime
     */
    private const CACHE_LIFETIME = '1 hour';

    private ClientFactory $clientFactory;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function search(string $searchQuery, bool $cacheResults = true): Collection
    {
        $closure = function () use ($searchQuery): Collection {
            $results = new Collection();
            foreach ($this->clientFactory->getClients() as $client) {
                $results = $client->search($searchQuery, self::RESULT_LIMIT);
                if ($results->isNotEmpty()) {
                    return $results;
                }
            }

            return $results;
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
