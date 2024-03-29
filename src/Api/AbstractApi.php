<?php

namespace Checkmango\Api;

use Checkmango\Client;
use Checkmango\HttpClient\Message\ResponseMediator;
use Checkmango\HttpClient\Util\JsonArray;
use Checkmango\HttpClient\Util\QueryStringBuilder;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractApi
{
    private const URI_PREFIX = '/api/';

    /**
     * @var \Checkmango\Client
     */
    private $client;

    /**
     * @var int|null
     */
    private $perPage;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function getClient(): Client
    {
        return $this->client;
    }

    protected function getAsResponse(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        if ($this->perPage !== null && ! isset($params['pagelen'])) {
            $params['pagelen'] = $this->perPage;
        }

        return $this->client->getHttpClient()->get(self::prepareUri($uri, $params), $headers);
    }

    protected function get(string $uri, array $params = [], array $headers = []): array
    {
        $response = $this->getAsResponse($uri, $params, $headers);

        return ResponseMediator::getContent($response);
    }

    protected function post(string $uri, array $params = [], array $headers = []): array
    {
        $body = self::prepareJsonBody($params);

        if ($body !== null) {
            $headers = self::addJsonContentType($headers);
        }

        return $this->postRaw($uri, $body, $headers);
    }

    protected function postRaw(string $uri, $body = null, array $headers = []): array
    {
        $response = $this->client->getHttpClient()->post(self::prepareUri($uri), $headers, $body ?? '');

        return ResponseMediator::getContent($response);
    }

    protected function put(string $uri, array $params = [], array $headers = []): array
    {
        $body = self::prepareJsonBody($params);

        if ($body !== null) {
            $headers = self::addJsonContentType($headers);
        }

        return $this->putRaw($uri, $body, $headers);
    }

    protected function putRaw(string $uri, $body = null, array $headers = []): array
    {
        $response = $this->client->getHttpClient()->put(self::prepareUri($uri), $headers, $body ?? '');

        return ResponseMediator::getContent($response);
    }

    protected function delete(string $uri, array $params = [], array $headers = []): array
    {
        $body = self::prepareJsonBody($params);

        if ($body !== null) {
            $headers = self::addJsonContentType($headers);
        }

        return $this->deleteRaw($uri, $body, $headers);
    }

    protected function deleteRaw(string $uri, $body = null, array $headers = []): array
    {
        $response = $this->client->getHttpClient()->delete(self::prepareUri($uri), $headers, $body ?? '');

        return ResponseMediator::getContent($response);
    }

    private static function prepareUri(string $uri, array $query = []): string
    {
        return sprintf('%s%s%s', self::URI_PREFIX, $uri, QueryStringBuilder::build($query));
    }

    private static function prepareJsonBody(array $params): ?string
    {
        if (count($params) === 0) {
            return null;
        }

        return JsonArray::encode($params);
    }

    private static function addJsonContentType(array $headers): array
    {
        return array_merge([ResponseMediator::CONTENT_TYPE_HEADER => ResponseMediator::JSON_CONTENT_TYPE], $headers);
    }
}
