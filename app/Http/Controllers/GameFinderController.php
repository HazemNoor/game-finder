<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameFinderRequest;
use BePark\GameFinder\GameFinder;
use BePark\GameFinder\GameResult;

class GameFinderController
{
    private GameFinderRequest $request;
    private GameFinder $finder;

    public function __construct(GameFinderRequest $request, GameFinder $finder)
    {
        $this->request = $request;
        $this->finder  = $finder;
    }

    public function __invoke(): array
    {
        $results = $this->finder->search(
            $this->request->query('search')
        );

        return $this->prepareResult($results);
    }

    private function prepareResult(array $results): array
    {
        $results = array_map(
            function (GameResult $result) {
                return [
                    'name'  => $result->getName(),
                    'image' => $result->getImage(),
                ];
            },
            $results
        );

        return ['results' => $results];
    }
}
