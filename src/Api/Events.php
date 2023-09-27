<?php

namespace Checkmango\Api;

use Checkmango\Client;
use Checkmango\HttpClient\Util\UriBuilder;

class Events extends AbstractApi
{
    /**
     * The team.
     *
     * @var int
     */
    protected $teamId;

    /**
     * Create a new events API instance.
     */
    public function __construct(Client $client, int $teamId)
    {
        parent::__construct($client);

        $this->teamId = $teamId;
    }

    /**
     * List events.
     *
     * @return array
     */
    public function list(array $params = [])
    {
        $uri = $this->buildEventsUri();

        return $this->get($uri, $params);
    }

    /**
     * Show an event.
     *
     * @return array
     */
    public function show(string $event, array $params = [])
    {
        $uri = $this->buildEventsUri($event);

        return $this->get($uri, $params);
    }

    /**
     * Create an event.
     *
     * @return array
     */
    public function create(string $event, array $params = [])
    {
        $uri = $this->buildEventsUri($event);

        return $this->post($uri, $params);
    }

    /**
     * Delete an event.
     *
     * @return array
     */
    public function remove(string $event, array $params = [])
    {
        $uri = $this->buildEventsUri($event);

        return $this->delete($uri, $params);
    }

    /**
     * Update an event.
     *
     * @return array
     */
    public function update(string $event, array $params = [])
    {
        $uri = $this->buildEventsUri($event);

        return $this->put($uri, $params);
    }

    /**
     * Build the events URI from the given parts.
     *
     * @return string
     */
    protected function buildEventsUri(string ...$parts)
    {
        return UriBuilder::build(
            'teams', $this->teamId,
            'events',
            ...$parts
        );
    }
}
