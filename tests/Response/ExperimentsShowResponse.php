<?php

namespace Prove\Tests\Response;

use GuzzleHttp\Psr7\Response;
use Prove\Tests\Resource;

class ExperimentsShowResponse
{
    public static function create()
    {
        $body = Resource::get('experiments-show-success.json');

        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }
}
