<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameFinderRequest;
use BePark\GameFinder\GameFinder;
use BePark\GameFinder\GameResult;
use Illuminate\Support\Collection;

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

    private function prepareResult(Collection $results): array
    {
        $results = $results->map(
            function (GameResult $result) {
                return [
                    'name'  => $result->getName(),
                    'image' => $result->getImage(),
                ];
            }
        );

        return ['results' => $results->all()];
    }
}
