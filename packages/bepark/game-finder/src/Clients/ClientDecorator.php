<?php

namespace BePark\GameFinder\Clients;

use BePark\GameFinder\Exceptions\ClientRuntimeException;
use Illuminate\Support\Collection;
use Throwable;

class ClientDecorator implements ClientInterface
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function search(string $searchQuery, int $resultLimit): Collection
    {
        $results = new Collection();

        try {
            $results = $this->client->search($searchQuery, $resultLimit);
        } catch (Throwable $e) {
            // Report client error to be investigated later and continue to the next client
            report(new ClientRuntimeException($e));
            /**
             * todo: We may implement Circuit Breaker Algorithm to prevent failures
             *       from constantly recurring after certain threshold of failures
             */
        }

        return $results;
    }
}
