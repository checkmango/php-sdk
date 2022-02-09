<?php

namespace Checkmango;

use Closure;
use Generator;
use Checkmango\Api\AbstractApi;
use Checkmango\Exception\RuntimeException;
use Checkmango\HttpClient\Message\ResponseMediator;
use ValueError;

final class ResultPager implements ResultPagerInterface
{
    /**
     * The default number of entries to request per page.
     *
     * @var int
     */
    private const PER_PAGE = 15;

    /**
     * The client to use for pagination.
     *
     * @var \Checkmango\Client
     */

    /**
     * The number of entries to request per page.
     *
     * @var int
     */
    private $perPage;

    /**
     * The pagination result from the API.
     *
     * @var array<string,string>
     */
    private $pagination;

    /**
     * Create a new result pager instance.
     *
     * @param  Client  $client
     * @param  int|null  $perPage
     * @return void
     */
    public function __construct(Client $client, int $perPage = null)
    {
        if (null !== $perPage && ($perPage < 1 || $perPage > 100)) {
            throw new ValueError(\sprintf('%s::__construct(): Argument #2 ($perPage) must be between 1 and 100, or null', self::class));
        }

        $this->client = $client;
        $this->perPage = $perPage ?? self::PER_PAGE;
        $this->pagination = [];
    }

    /**
     * @inheritDoc
     */
    public function fetch(AbstractApi $api, string $method, array $parameters = []): array
    {
        $result = self::bindPerPage($api, $this->perPage)->$method(...$parameters);

        if (! \is_array($result)) {
            throw new RuntimeException('Pagination of this endpoint is not supported.');
        }

        $this->postFetch();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function fetchAll(AbstractApi $api, string $method, array $parameters = []): array
    {
        return \iterator_to_array($this->fetchAllLazy($api, $method, $parameters));
    }

    /**
     * @inheritDoc
     */
    public function fetchAllLazy(AbstractApi $api, string $method, array $parameters = []): Generator
    {
        /** @var mixed $value */
        foreach ($this->fetch($api, $method, $parameters) as $value) {
            yield $value;
        }

        while ($this->hasNext()) {
            /** @var mixed $value */
            foreach ($this->fetchNext() as $value) {
                yield $value;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function hasNext(): bool
    {
        return isset($this->pagination['next']);
    }

    /**
     * @inheritDoc
     */
    public function fetchNext(): array
    {
        return $this->get('next');
    }

    /**
     * @inheritDoc
     */
    public function hasPrevious(): bool
    {
        return isset($this->pagination['prev']);
    }

    /**
     * @inheritDoc
     */
    public function fetchPrevious(): array
    {
        return $this->get('prev');
    }

    /**
     * @inheritDoc
     */
    public function fetchFirst(): array
    {
        return $this->get('first');
    }

    /**
     * @inheritDoc
     */
    public function fetchLast(): array
    {
        return $this->get('last');
    }

    /**
     * Refresh the pagination property.
     *
     * @return void
     */
    private function postFetch(): void
    {
        $response = $this->client->getLastResponse();

        $this->pagination = null === $response ? [] : ResponseMediator::getPagination($response);
    }

    /**
     * @param  string  $key
     * @return array
     *
     * @throws \Http\Client\Exception
     */
    private function get(string $key): array
    {
        $pagination = $this->pagination[$key] ?? null;

        if (null === $pagination) {
            return [];
        }

        $result = $this->client->getHttpClient()->get($pagination);

        $content = ResponseMediator::getContent($result);

        if (! \is_array($content)) {
            throw new RuntimeException('Pagination of this endpoint is not supported.');
        }

        $this->postFetch();

        return $content;
    }

    /**
     * @param  \Checkmango\Api\AbstractApi  $api
     * @param  int  $perPage
     * @return \Checkmango\Api\AbstractApi
     */
    private static function bindPerPage(AbstractApi $api, int $perPage): AbstractApi
    {
        $closure = Closure::bind(static function (AbstractApi $api) use ($perPage): AbstractApi {
            $clone = clone $api;

            $clone->perPage = $perPage;

            return $clone;
        }, null, AbstractApi::class);

        /** @var AbstractApi */
        return $closure($api);
    }
}
