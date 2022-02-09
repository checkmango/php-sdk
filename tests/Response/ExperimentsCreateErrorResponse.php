<?php

namespace Checkmango\Tests\Response;

use GuzzleHttp\Psr7\Response;
use Checkmango\Tests\Resource;

class ExperimentsCreateErrorResponse
{
    public static function create()
    {
        $body = Resource::get('experiments-create-error.json');

        return new Response(422, ['Content-Type' => 'application/json'], $body);
    }
}
