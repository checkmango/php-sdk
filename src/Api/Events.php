<?php

namespace Prove\Api;

use Prove\Client;
use Prove\HttpClient\Util\UriBuilder;

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
     *
     * @param  \Prove\Client  $client
     * @param  int  $teamId
     */
    public function __construct(Client $client, int $teamId)
    {
        parent::__construct($client);

        $this->teamId = $teamId;
    }

    /**
     * List events.
     *
     * @param  array  $params
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
     * @param  string  $event
     * @param  array  $params
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
     * @param  string  $event
     * @param  array  $params
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
     * @param  string  $event
     * @param  array  $params
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
     * @param  string  $event
     * @param  array  $params
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
     * @param  string  ...$parts
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
