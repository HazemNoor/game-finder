<?php

namespace BePark\GameFinder;

class GameResult
{
    private string $name;
    private ?string $image;

    public function __construct(string $name, ?string $image)
    {
        $this->name  = $name;
        $this->image = $image;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): ?string
    {
        // todo: Return a proxy URL from our service instead of the original image URL
        return $this->image;
    }
}
