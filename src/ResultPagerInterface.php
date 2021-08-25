<?php

namespace Prove;

use Generator;
use Prove\Api\AbstractApi;

interface ResultPagerInterface
{
    /**
     * Fetch a single result from an api call.
     *
     * @param AbstractApi $api
     * @param string      $method
     * @param array       $parameters
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetch(AbstractApi $api, string $method, array $parameters = []): array;

    /**
     * Fetch all results from an api call.
     *
     * @param AbstractApi $api
     * @param string      $method
     * @param array       $parameters
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchAll(AbstractApi $api, string $method, array $parameters = []): array;

    /**
     * Lazily fetch all results from an api call.
     *
     * @param AbstractApi $api
     * @param string      $method
     * @param array       $parameters
     *
     * @throws \Http\Client\Exception
     *
     * @return \Generator
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
     * @throws \Http\Client\Exception
     *
     * @return array
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
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchPrevious(): array;

    /**
     * Fetch the first page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchFirst(): array;

    /**
     * Fetch the last page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchLast(): array;
}
