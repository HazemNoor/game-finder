<?php

namespace BePark\GameFinder\Factories;

use BePark\GameFinder\Clients\AbstractClient;
use Illuminate\Support\Collection;

class ClientFactory
{
    /**
     * @var Collection|AbstractClient[]
     */
    private Collection $clients;

    public function __construct()
    {
        $this->clients = new Collection();
    }

    public function addClient(AbstractClient $client)
    {
        $this->clients->add($client);
    }

    /**
     * @return Collection|AbstractClient[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }
}
