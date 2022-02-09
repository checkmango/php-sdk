<?php

namespace Checkmango\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

final class Authentication implements Plugin
{
    private $header;

    public function __construct(string $token)
    {
        $this->header = sprintf('Bearer %s', $token);
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $request = $request->withHeader('Authorization', $this->header);

        return $next($request);
    }
}
