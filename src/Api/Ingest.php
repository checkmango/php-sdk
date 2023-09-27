<?php

namespace Checkmango\Api;

use Checkmango\Client;
use Checkmango\HttpClient\Util\UriBuilder;

class Ingest extends AbstractApi
{
    /**
     * The team.
     *
     * @var int
     */
    protected $teamId;

    /**
     * Create a new Ingest API instance.
     *
     * @param  \Checkmango\Client  $client
     * @param  int  $teamId
     */
    public function __construct(Client $client, int $teamId)
    {
        parent::__construct($client);

        $this->teamId = $teamId;
    }

    /**
     * Store an ingest.
     */
    public function store(string $experiment, string $participant, string $variant, string $event = null)
    {
        $uri = $this->buildIngestUri();

        return $this->post($uri, [
            'experiment' => $experiment,
            'participant' => $participant,
            'variant' => $variant,
            'event' => $event,
        ]);
    }

    /**
     * Build the ingest URI from the given parts.
     *
     * @param  string  ...$parts
     * @return string
     */
    protected function buildIngestUri(string ...$parts)
    {
        return UriBuilder::build(
            'teams', $this->teamId,
            'ingest'
        );
    }
}
