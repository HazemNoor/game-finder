<?php

namespace BePark\GameFinder\Clients;

use BePark\GameFinder\GameResult;
use Illuminate\Support\Collection;
use MarcReichel\IGDBLaravel\Models\Cover;
use MarcReichel\IGDBLaravel\Models\Game;

class IgdbClient extends AbstractClient
{
    protected function doSearch(string $searchQuery, int $resultLimit): Collection
    {
        $games = Game::search($searchQuery)->offset(0)->limit($resultLimit)->get();

        $results = new Collection();

        foreach ($games as $game) {
            $imageUrl = null;

            if (is_int($game['cover'])) {
                $image = Cover::find($game['cover']);
                if (!is_null($image)) {
                    $imageUrl = sprintf(
                        'https://images.igdb.com/igdb/image/upload/t_cover_big/%s.jpg',
                        $image['image_id']
                    );
                }
            }

            $results->add(new GameResult($game['name'], $imageUrl));
        }

        return $results;
    }
}
