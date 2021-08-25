<?php

namespace Prove\HttpClient\Util;

final class QueryStringBuilder
{
    /**
     * Encode a query as a string according to RFC 3986.
     *
     * @param  array  $query
     * @return string
     */
    public static function build(array $query): string
    {
        if (0 === count($query)) {
            return '';
        }

        return sprintf('?%s', http_build_query($query, '', '&', PHP_QUERY_RFC3986));
    }
}
