<?php

namespace HazemNoor\GameFinder\Tests;

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Tests\TestCase;

class ApiTest extends TestCase
{
    private const BASE_URL    = '/api/games';
    private const API_HEADERS = ['Accept' => 'application/vnd.api+json'];
    private const QUERY       = 'Red Alert 2';

    public function testSearchQueryParameterRequired()
    {
        $response = $this->get(self::BASE_URL, self::API_HEADERS);
        $response->assertStatus(Httpstatuscodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSearchQueryParameterNotBeEmpty()
    {
        $response = $this->get(
            self::BASE_URL . '?' . http_build_query(['search' => '']),
            self::API_HEADERS
        );
        $response->assertStatus(Httpstatuscodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSearchQueryParameterNotHaveScalarValue()
    {
        $response = $this->get(
            self::BASE_URL . '?' . http_build_query(['search' => [self::QUERY]]),
            self::API_HEADERS
        );
        $response->assertStatus(Httpstatuscodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSearchQueryParameterHaveScalarValue()
    {
        $response = $this->get(
            self::BASE_URL . '?' . http_build_query(['search' => self::QUERY]),
            self::API_HEADERS
        );
        $response->assertStatus(Httpstatuscodes::HTTP_OK);
    }
}
