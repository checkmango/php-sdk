<?php

namespace Checkmango\Tests\Response;

use GuzzleHttp\Psr7\Response;
use Checkmango\Tests\Resource;

class ExperimentsShowResponse
{
    public static function create()
    {
        $body = Resource::get('experiments-show-success.json');

        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }
}
