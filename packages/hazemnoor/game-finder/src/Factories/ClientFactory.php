<?php

namespace HazemNoor\GameFinder\Factories;

use HazemNoor\GameFinder\Clients\ClientDecorator;
use HazemNoor\GameFinder\Clients\ClientInterface;
use Illuminate\Support\Collection;

class ClientFactory
{
    /**
     * @var Collection|ClientInterface[]
     */
    private Collection $clients;

    public function __construct()
    {
        $this->clients = new Collection();
    }

    public function addClient(ClientInterface $client)
    {
        $this->clients->add(new ClientDecorator($client));
    }

    /**
     * @return Collection|ClientInterface[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }
}
