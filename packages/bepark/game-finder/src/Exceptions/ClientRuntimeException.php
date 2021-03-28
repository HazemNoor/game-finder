<?php

namespace BePark\GameFinder\Exceptions;

use Exception;
use Throwable;

class ClientRuntimeException extends Exception
{
    public function __construct(Throwable $previous)
    {
        parent::__construct('', 0, $previous);
    }
}
