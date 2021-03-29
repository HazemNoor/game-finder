<?php

namespace BePark\GameFinder\Clients;

use BePark\GameFinder\Models\GameResult;
use Illuminate\Support\Collection;

interface ClientInterface
{
    /**
     * @param string $searchQuery
     * @param int    $resultLimit
     *
     * @return Collection|GameResult[]
     */
    public function search(string $searchQuery, int $resultLimit): Collection;
}
