<?php

namespace Checkmango\Tests;

use Http\Mock\Client as MockClient;
use Checkmango\Client;
use Checkmango\HttpClient\Builder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class MockedClient
{
    public static function create(ResponseInterface $response)
    {
        $client = new MockClient(self::createResponseFactory($response));

        return new Client(new Builder($client));
    }

    private static function createResponseFactory(ResponseInterface $response)
    {
        return new class($response) implements ResponseFactoryInterface
        {
            private $response;

            public function __construct(ResponseInterface $response)
            {
                $this->response = $response;
            }

            public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
            {
                return $this->response;
            }
        };
    }
}
