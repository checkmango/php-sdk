<?php

namespace Checkmango\Tests\Response;

use Checkmango\Tests\Resource;
use GuzzleHttp\Psr7\Response;

class ExperimentsListResponse
{
    public static function create()
    {
        $body = Resource::get('experiments-list-success.json');

        return new Response(200, ['Content-Type' => 'application/vnd.api+json'], $body);
    }
}
