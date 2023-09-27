<?php

namespace Checkmango\HttpClient\Message;

use Checkmango\Exception\RuntimeException;
use Checkmango\HttpClient\Util\JsonArray;
use Psr\Http\Message\ResponseInterface;

final class ResponseMediator
{
    /**
     * The content type header.
     *
     * @var string
     */
    public const CONTENT_TYPE_HEADER = 'Content-Type';

    /**
     * The JSON content type identifier.
     *
     * @var string
     */
    public const JSON_CONTENT_TYPE = 'application/vnd.api+json';

    /**
     * Get the decoded response content.
     *
     * If the there is no response body, we will always return the empty array.
     *
     * @return array
     *
     * @throws \Checkmango\Exception\RuntimeException
     */
    public static function getContent(ResponseInterface $response, $key = 'data')
    {
        if ($response->getStatusCode() === 204) {
            return [];
        }

        $body = (string) $response->getBody();

        if ($body === '') {
            return [];
        }

        if (strpos($response->getHeaderLine(self::CONTENT_TYPE_HEADER), self::JSON_CONTENT_TYPE) !== 0) {
            throw new RuntimeException(sprintf('The content type was not %s.', self::JSON_CONTENT_TYPE));
        }

        $data = JsonArray::decode($body);

        return $data[$key] ?? $data;
    }

    /**
     * Get the pagination data from the response.
     *
     * @return array<string,string>
     */
    public static function getPagination(ResponseInterface $response): array
    {
        try {
            /** @var array<string,string> */
            return array_filter(self::getContent($response, 'links'), [self::class, 'paginationFilter'], ARRAY_FILTER_USE_KEY);
        } catch (RuntimeException $e) {
            return [];
        }
    }

    /**
     * @param  string|int  $key
     * @return bool
     */
    private static function paginationFilter($key)
    {
        return in_array($key, ['first', 'last', 'prev', 'next'], true);
    }

    /**
     * Get the error message from the response if present.
     */
    public static function getErrorMessage(ResponseInterface $response): ?string
    {
        try {
            /** @var scalar|array */
            $error = self::getContent($response)['error'] ?? self::getContent($response) ?? null;
        } catch (RuntimeException $e) {
            return null;
        }

        return is_array($error) ? self::getMessageFromError($error) : null;
    }

    /**
     * Get the error message from the error array if present.
     */
    private static function getMessageFromError(array $error): ?string
    {
        /** @var scalar|array */
        $message = $error['message'] ?? '';

        if (! is_string($message)) {
            return null;
        }

        $detail = self::getDetailAsString($error);

        if ($message !== '') {
            return $detail !== '' ? sprintf('%s: %s', $message, $detail) : $message;
        }

        if ($detail !== '') {
            return $detail;
        }

        return null;
    }

    /**
     * Present the detail portion of the error array.
     */
    private static function getDetailAsString(array $error): string
    {
        /** @var string|array $detail */
        $detail = $error['detail'] ?? '';

        if ($detail === '' || $detail === []) {
            return '';
        }

        return (string) strtok(is_string($detail) ? $detail : JsonArray::encode($detail), "\n");
    }

    private static function handleError(ResponseInterface $response)
    {
        // throw new Exception();
    }
}
