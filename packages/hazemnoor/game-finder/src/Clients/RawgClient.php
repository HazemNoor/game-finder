<?php

namespace HazemNoor\GameFinder\Clients;

use HazemNoor\GameFinder\Models\GameResult;
use Illuminate\Support\Collection;
use Rawg\ApiClient;
use Rawg\Config;
use Rawg\Filters\GamesFilter;

class RawgClient implements ClientInterface
{
    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient(
            new Config(env('RAWG_CLIENT_API_KEY', ''), 'en')
        );
    }

    /**
     * @inheritDoc
     */
    public function search(string $searchQuery, int $resultLimit): Collection
    {
        $gamesFilter = (new GamesFilter())
            ->setPage(1)
            ->setPageSize($resultLimit)
            ->setSearch($searchQuery)
            ->setPrecise()
        ;

        $response = $this->apiClient->games()->getGames($gamesFilter)->getData();

        $results = new Collection();

        foreach ($response['results'] as $game) {
            $results->add(new GameResult($game['name'], $game['background_image']));
        }

        return $results;
    }
}
