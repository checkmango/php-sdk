<?php

namespace Checkmango\Tests;

use Checkmango\Exception\RuntimeException;
use Checkmango\HttpClient\Message\ResponseMediator;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;

class ResponseMediatorTest extends TestCase
{
    public function testGetContent(): void
    {
        $response = new Response(
            200,
            ['Content-Type' => 'application/vnd.api+json'],
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
        $this->expectExceptionMessage('The content type was not application/vnd.api+json.');

        ResponseMediator::getContent($response);
    }

    public function testGetContentInvalidJson(): void
    {
        $body = 'foobar';
        $response = new Response(
            200,
            ['Content-Type' => 'application/vnd.api+json'],
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
            ['Content-Type' => 'application/vnd.api+json'],
            Utils::streamFor($body)
        );

        $this->assertNull(ResponseMediator::getErrorMessage($response));
    }
}
