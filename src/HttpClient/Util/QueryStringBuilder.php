<?php

namespace Checkmango\HttpClient\Util;

final class QueryStringBuilder
{
    /**
     * Encode a query as a string according to RFC 3986.
     */
    public static function build(array $query): string
    {
        if (count($query) === 0) {
            return '';
        }

        return sprintf('?%s', http_build_query($query, '', '&', PHP_QUERY_RFC3986));
    }
}
