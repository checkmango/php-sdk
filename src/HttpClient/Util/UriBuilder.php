<?php

namespace Checkmango\HttpClient\Util;

use ValueError;

final class UriBuilder
{
    public static function build(string ...$parts): string
    {
        foreach ($parts as $index => $part) {
            if ('' === $part) {
                throw new ValueError(\sprintf('%s::buildUri(): Argument #%d ($parts) must non-empty', self::class, $index + 1));
            }

            $parts[$index] = rawurlencode($part);
        }

        return implode('/', $parts);
    }

    public static function appendSeparator(string $uri): string
    {
        return sprintf('%s%s', $uri, '/');
    }
}
