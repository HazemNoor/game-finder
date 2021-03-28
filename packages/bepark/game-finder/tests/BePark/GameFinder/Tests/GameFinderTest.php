<?php

namespace BePark\GameFinder\Tests;

use BePark\GameFinder\Clients\AbstractClient;
use BePark\GameFinder\Clients\IgdbClient;
use BePark\GameFinder\Clients\RawgClient;
use BePark\GameFinder\Exceptions\ClientInvalidInterfaceException;
use BePark\GameFinder\GameFinder;
use BePark\GameFinder\GameResult;
use Exception;
use Illuminate\Support\Collection;
use stdClass;
use Tests\TestCase;

class GameFinderTest extends TestCase
{
    /**
     * Test Client class must extend AbstractClient
     */
    public function testClientInvalidInterfaceException()
    {
        $this->expectException(ClientInvalidInterfaceException::class);

        new GameFinder([new stdClass()]); // Providing any wrong class
    }

    /**
     * Test if client throws any type of Exception it won't be thrown and just reported and return an empty Collection
     */
    public function testClientRuntimeExceptionSuppress()
    {
        $client = $this->getMockForAbstractClass(AbstractClient::class);

        $client
            ->expects($this->once())
            ->method('doSearch')
            ->withAnyParameters()
            ->willThrowException(new Exception())
        ;

        $results = $client->search('A Game Name', 10);

        $this->assertEquals(new Collection(), $results);
    }

    /**
     * Test if different clients returned results have duplicate games data, duplicates will be removed
     */
    public function testRemoveDuplicateResults()
    {
        $collectionA = new Collection(
            [
                new GameResult('Game 1', 'https://example1.com/game1.jpg'),
                new GameResult('Game 2', 'https://example1.com/game2.jpg'),
                new GameResult('Game 3', 'https://example1.com/game3.jpg'),
                new GameResult('Game 4', 'https://example1.com/game4.jpg'),
                new GameResult('Game 5', 'https://example1.com/game5.jpg'),
            ]
        );
        $collectionB = new Collection(
            [
                new GameResult('Game 4', 'https://example2.com/game4.jpg'),
                new GameResult('Game 5', 'https://example2.com/game5.jpg'),
                new GameResult('Game 6', 'https://example2.com/game6.jpg'),
                new GameResult('Game 7', 'https://example2.com/game7.jpg'),
                new GameResult('Game 8', 'https://example2.com/game8.jpg'),
            ]
        );

        $expectedCollection = new Collection(
            [
                new GameResult('Game 1', 'https://example1.com/game1.jpg'),
                new GameResult('Game 2', 'https://example1.com/game2.jpg'),
                new GameResult('Game 3', 'https://example1.com/game3.jpg'),
                new GameResult('Game 4', 'https://example1.com/game4.jpg'),
                new GameResult('Game 5', 'https://example1.com/game5.jpg'),
                new GameResult('Game 6', 'https://example2.com/game6.jpg'),
                new GameResult('Game 7', 'https://example2.com/game7.jpg'),
                new GameResult('Game 8', 'https://example2.com/game8.jpg'),
            ]
        );

        $clientA = $this->getMockBuilder(RawgClient::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $clientA
            ->expects($this->once())
            ->method('search')
            ->withAnyParameters()
            ->willReturn($collectionA)
        ;

        $clientB = $this->getMockBuilder(IgdbClient::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $clientB
            ->expects($this->once())
            ->method('search')
            ->withAnyParameters()
            ->willReturn($collectionB)
        ;

        $finder = new GameFinder([$clientA, $clientB]);

        $results = $finder->search('A Game Name', false);

        $this->assertEquals($expectedCollection->all(), $results);
    }
}
