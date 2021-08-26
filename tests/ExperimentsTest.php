<?php

namespace Prove\Tests;

use PHPUnit\Framework\TestCase;
use Prove\Tests\Response\ExperimentsListResponse;
use Prove\Tests\Response\ExperimentsShowResponse;

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
}
