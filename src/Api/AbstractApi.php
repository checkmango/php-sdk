<?php

namespace Prove\Api;

use Prove\Client;
use Prove\HttpClient\Message\ResponseMediator;
use Prove\HttpClient\Util\JsonArray;
use Prove\HttpClient\Util\QueryStringBuilder;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractApi
{
    private const URI_PREFIX = '/api/';

    /**
     * @var \Prove\Client
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
        if (null !== $this->perPage && ! isset($params['pagelen'])) {
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

        if (null !== $body) {
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

        if (null !== $body) {
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

        if (null !== $body) {
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
        if (0 === count($params)) {
            return null;
        }

        return JsonArray::encode($params);
    }

    private static function addJsonContentType(array $headers): array
    {
        return array_merge([ResponseMediator::CONTENT_TYPE_HEADER => ResponseMediator::JSON_CONTENT_TYPE], $headers);
    }
}
