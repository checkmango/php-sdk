<?php

namespace Prove\Tests;

use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prove\Exception\RuntimeException;
use Prove\HttpClient\Message\ResponseMediator;

class ResponseMediatorTest extends TestCase
{
    public function testGetContent(): void
    {
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            Utils::streamFor('{"data": {"foo": "bar"}}')
        );

        $this->assertSame(['foo' => 'bar'], ResponseMediator::getContent($response));
    }

    public function testGetContentNotJson(): void
    {
        $body = 'foobar';
        $response = new Response(
            200,
            [],
            Utils::streamFor($body)
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The content type was not application/json.');

        ResponseMediator::getContent($response);
    }

    public function testGetContentInvalidJson(): void
    {
        $body = 'foobar';
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            Utils::streamFor($body)
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('json_decode error: Syntax error');

        ResponseMediator::getContent($response);
    }

    public function testGetErrorMessageInvalidJson(): void
    {
        $body = 'foobar';
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            Utils::streamFor($body)
        );

        $this->assertNull(ResponseMediator::getErrorMessage($response));
    }
}
