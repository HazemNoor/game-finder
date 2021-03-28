<?php

namespace BePark\GameFinder\Clients;

use BePark\GameFinder\Exceptions\ClientRuntimeException;
use BePark\GameFinder\GameResult;
use Illuminate\Support\Collection;
use Throwable;

abstract class AbstractClient
{
    /**
     * @param string $searchQuery
     * @param int    $resultLimit
     *
     * @return Collection|GameResult[]
     */
    public function search(string $searchQuery, int $resultLimit): Collection
    {
        $results = new Collection();

        try {
            $results = $this->doSearch($searchQuery, $resultLimit);
        } catch (Throwable $e) {
            // Report client error to be investigated later and continue to the next client
            report(new ClientRuntimeException($e));

            /**
             * todo: We may implement Circuit Breaker Algorithm to preventing failures
             *       from constantly recurring after certain threshold of failures
             */
        }

        return $results;
    }

    abstract protected function doSearch(string $searchQuery, int $resultLimit): Collection;
}
