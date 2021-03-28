<?php

namespace BePark\GameFinder\Exceptions;

use Exception;

class ClientInvalidInterfaceException extends Exception
{
    public function __construct(string $client, string $baseClient)
    {
        parent::__construct(sprintf('Client "%s" must extend class "%s"', $client, $baseClient));
    }
}
