<?php

namespace Checkmango\Tests;

use RuntimeException;

final class Resource
{
    /**
     * @return string
     */
    public static function get(string $path)
    {
        $content = file_get_contents(sprintf('%s/Resource/%s', __DIR__, $path));

        if ($content === false) {
            throw new RuntimeException(sprintf('Unable to read resource [%s].', $path));
        }

        return $content;
    }
}
