<?php

namespace BePark\GameFinder;

use BePark\GameFinder\Clients\AbstractClient;
use BePark\GameFinder\Clients\IgdbClient;
use BePark\GameFinder\Clients\RawgClient;
use BePark\GameFinder\Exceptions\ClientInvalidInterfaceException;
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

    public function __construct(array $clients = [])
    {
        if (empty($clients)) {
            // todo: Move default values to config
            $clients = [
                new RawgClient(),
                new IgdbClient(),
            ];
        }

        foreach ($clients as $client) {
            if (!$client instanceof AbstractClient) {
                throw new ClientInvalidInterfaceException(get_class($client), AbstractClient::class);
            }
        }

        $this->clients = $clients;
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
