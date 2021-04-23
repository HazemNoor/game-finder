<?php

namespace HazemNoor\GameFinder\Tests;

use HazemNoor\GameFinder\Clients\ClientDecorator;
use HazemNoor\GameFinder\Clients\RawgClient;
use Exception;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GameFinderTest extends TestCase
{
    /**
     * Test if client throws any type of Exception it won't be thrown and just reported and return an empty Collection
     */
    public function testClientRuntimeExceptionSuppress()
    {
        $client = $this->getMockBuilder(RawgClient::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $client
            ->expects($this->once())
            ->method('search')
            ->with('A Game Name', 10)
            ->willThrowException(new Exception())
        ;

        $clientDecorator = new ClientDecorator($client);

        $results = $clientDecorator->search('A Game Name', 10);

        $this->assertEquals(new Collection(), $results);
    }
}
