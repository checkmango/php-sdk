<?php

namespace Checkmango\Tests;

use Checkmango\Exception\ValidationFailedException;
use Checkmango\Tests\Response\ExperimentsCreateErrorResponse;
use Checkmango\Tests\Response\ExperimentsListResponse;
use Checkmango\Tests\Response\ExperimentsShowResponse;
use PHPUnit\Framework\TestCase;

class ExperimentsTest extends TestCase
{
    public function testExperimentsList()
    {
        $client = MockedClient::create(
            ExperimentsListResponse::create()
        );

        $response = $client->teams(1)->experiments()->list();

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
    }

    public function testExperimentsShow()
    {
        $client = MockedClient::create(
            ExperimentsShowResponse::create()
        );

        $response = $client->teams(1)->experiments()->show('BASKET_AUGUST_2021');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertSame('BASKET_AUGUST_2021', $response['id']);
    }

    public function testExperimentsCreateError()
    {
        $client = MockedClient::create(
            ExperimentsCreateErrorResponse::create()
        );

        $this->expectException(ValidationFailedException::class);

        $response = $client->teams(1)->experiments()->create('NEW_EXPERIMENT');

        $this->assertIsArray($response);
        $this->assertIsArray($response['errors']);
    }
}
