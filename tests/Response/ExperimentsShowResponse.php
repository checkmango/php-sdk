<?php

namespace Checkmango\Tests\Response;

use Checkmango\Tests\Resource;
use GuzzleHttp\Psr7\Response;

class ExperimentsShowResponse
{
    public static function create()
    {
        $body = Resource::get('experiments-show-success.json');

        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }
}
