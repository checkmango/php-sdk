<?php

namespace Checkmango;

use Checkmango\Api\AbstractApi;
use Checkmango\Exception\RuntimeException;
use Checkmango\HttpClient\Message\ResponseMediator;
use Closure;
use Generator;
use ValueError;

final class ResultPager implements ResultPagerInterface
{
    /**
     * The default number of entries to request per page.
     *
     * @var int
     */
    protected const PER_PAGE = 15;

    /**
     * The client to use for pagination.
     *
     * @var \Checkmango\Client
     */
    protected $client;

    /**
     * The number of entries to request per page.
     *
     * @var int
     */
    protected $perPage;

    /**
     * The pagination result from the API.
     *
     * @var array<string,string>
     */
    protected $pagination;

    /**
     * Create a new result pager instance.
     *
     * @return void
     */
    public function __construct(Client $client, int $perPage = null)
    {
        if ($perPage !== null && ($perPage < 1 || $perPage > 100)) {
            throw new ValueError(\sprintf('%s::__construct(): Argument #2 ($perPage) must be between 1 and 100, or null', self::class));
        }

        $this->client = $client;
        $this->perPage = $perPage ?? self::PER_PAGE;
        $this->pagination = [];
    }

    /**
     * {@inheritDoc}
     */
    public function fetch(AbstractApi $api, string $method, array $parameters = []): array
    {
        $result = self::bindPerPage($api, $this->perPage)->$method($parameters);

        if (! \is_array($result)) {
            throw new RuntimeException('Pagination of this endpoint is not supported.');
        }

        $this->postFetch();

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAll(AbstractApi $api, string $method, array $parameters = []): array
    {
        return \iterator_to_array($this->fetchAllLazy($api, $method, $parameters));
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function hasNext(): bool
    {
        return $this->pagination['next'] !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchNext(): array
    {
        return $this->get('next');
    }

    /**
     * {@inheritDoc}
     */
    public function hasPrevious(): bool
    {
        return $this->pagination['prev'] !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchPrevious(): array
    {
        return $this->get('prev');
    }

    /**
     * {@inheritDoc}
     */
    public function fetchFirst(): array
    {
        return $this->get('first');
    }

    /**
     * {@inheritDoc}
     */
    public function fetchLast(): array
    {
        return $this->get('last');
    }

    /**
     * Refresh the pagination property.
     */
    private function postFetch(): void
    {
        $response = $this->client->getLastResponse();

        $this->pagination = $response === null ? [] : ResponseMediator::getPagination($response);
    }

    /**
     * @throws \Http\Client\Exception
     */
    private function get(string $key): array
    {
        $pagination = $this->pagination[$key] ?? null;

        if ($pagination === null) {
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
