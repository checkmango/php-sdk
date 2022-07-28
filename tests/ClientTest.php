<?php

namespace Checkmango\Tests;

use Checkmango\Client;
use Checkmango\Exception\EndpointNotFoundException;
use Checkmango\Exception\ResourceNotFoundException;
use Checkmango\Exception\ServerException;
use Checkmango\Exception\ValidationFailedException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testCreateClient()
    {
        $client = new Client();

        self::assertInstanceOf(Client::class, $client);
        self::assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testExceptionThrownWhenResourceNotFound()
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('Resource Not Found');
        $this->expectExceptionCode(404);

        $client = MockedClient::create(new Response(
            404,
            ['Content-Type' => 'application/vnd.api+json'],
            Utils::streamFor('{"message": "Resource Not Found"}')
        ));

        $client->teams(1)->experiments()->list();
    }

    public function testExceptionThrownWhenEndpointNotFound()
    {
        $this->expectException(EndpointNotFoundException::class);
        $this->expectExceptionMessage('Not Found');
        $this->expectExceptionCode(404);

        $client = MockedClient::create(new Response(
            404,
            ['Content-Type' => 'application/vnd.api+json'],
            Utils::streamFor('{"message": "Not Found"}')
        ));

        $client->teams(1)->experiments()->list();
    }

    public function testExceptionThrownWhenClientReturnsError()
    {
        $this->expectException(ValidationFailedException::class);
        $this->expectExceptionMessage('The given data was invalid.');
        $this->expectExceptionCode(422);

        $client = MockedClient::create(new Response(
            422,
            ['Content-Type' => 'application/vnd.api+json'],
            Utils::streamFor('{"message": "The given data was invalid.","errors": {"key": ["The key field is required."]}}')
        ));

        $client->teams(1)->experiments()->list();
    }

    public function testExceptionThrownWhenServerReturnsError()
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('Server Error');

        $client = MockedClient::create(new Response(
            500,
            ['Content-Type' => 'application/vnd.api+json'],
            Utils::streamFor('Server Error')
        ));

        $client->teams(1)->experiments()->list();
    }
}
