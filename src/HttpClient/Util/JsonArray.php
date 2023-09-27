<?php

namespace Checkmango\HttpClient\Util;

use Checkmango\Exception\RuntimeException;

final class JsonArray
{
    /**
     * Decode a JSON string into a PHP array.
     *
     *
     * @throws \Checkmango\Exception\RuntimeException
     */
    public static function decode(string $json): array
    {
        /** @var scalar|array|null $data */
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(sprintf('json_decode error: %s', json_last_error_msg()));
        }

        if ($data === null || ! is_array($data)) {
            throw new RuntimeException(sprintf('json_decode error: Expected JSON of type array, %s given.', get_debug_type($data)));
        }

        return $data;
    }

    /**
     * Encode a PHP array into a JSON string.
     *
     *
     * @throws \Checkmango\Exception\RuntimeException
     */
    public static function encode(array $value): string
    {
        $json = json_encode($value);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(sprintf('json_encode error: %s', json_last_error_msg()));
        }

        /** @var string */
        return $json;
    }
}
