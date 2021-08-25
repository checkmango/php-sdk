<?php

namespace Prove\Tests;

use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;
use Prove\Client;

final class ClientTest extends TestCase
{
    public function testCreateClient()
    {
        $client = new Client();

        self::assertInstanceOf(Client::class, $client);
        self::assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }
}
