<?php

namespace Checkmango;

use Checkmango\Api\AbstractApi;
use Generator;

interface ResultPagerInterface
{
    /**
     * Fetch a single result from an api call.
     *
     * @param  AbstractApi  $api
     * @param  string  $method
     * @param  array  $parameters
     * @return array
     *
     * @throws \Http\Client\Exception
     */
    public function fetch(AbstractApi $api, string $method, array $parameters = []): array;

    /**
     * Fetch all results from an api call.
     *
     * @param  AbstractApi  $api
     * @param  string  $method
     * @param  array  $parameters
     * @return array
     *
     * @throws \Http\Client\Exception
     */
    public function fetchAll(AbstractApi $api, string $method, array $parameters = []): array;

    /**
     * Lazily fetch all results from an api call.
     *
     * @param  AbstractApi  $api
     * @param  string  $method
     * @param  array  $parameters
     * @return \Generator
     *
     * @throws \Http\Client\Exception
     */
    public function fetchAllLazy(AbstractApi $api, string $method, array $parameters = []): Generator;

    /**
     * Check to determine the availability of a next page.
     *
     * @return bool
     */
    public function hasNext(): bool;

    /**
     * Fetch the next page.
     *
     * @return array
     *
     * @throws \Http\Client\Exception
     */
    public function fetchNext(): array;

    /**
     * Check to determine the availability of a previous page.
     *
     * @return bool
     */
    public function hasPrevious(): bool;

    /**
     * Fetch the previous page.
     *
     * @return array
     *
     * @throws \Http\Client\Exception
     */
    public function fetchPrevious(): array;

    /**
     * Fetch the first page.
     *
     * @return array
     *
     * @throws \Http\Client\Exception
     */
    public function fetchFirst(): array;

    /**
     * Fetch the last page.
     *
     * @return array
     *
     * @throws \Http\Client\Exception
     */
    public function fetchLast(): array;
}
