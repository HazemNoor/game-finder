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
            $exception = new ClientRuntimeException($e);

            // Report client error to be investigated later and continue to next client
            report($exception);
        }

        return $results;
    }

    abstract protected function doSearch(string $searchQuery, int $resultLimit): Collection;
}
