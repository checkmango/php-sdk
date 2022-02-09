<?php

namespace Checkmango\Tests\Response;

use GuzzleHttp\Psr7\Response;
use Checkmango\Tests\Resource;

class ExperimentsListResponse
{
    public static function create()
    {
        $body = Resource::get('experiments-list-success.json');

        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }
}
