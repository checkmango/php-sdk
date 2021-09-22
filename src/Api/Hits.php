<?php

namespace Prove\Api;

use Prove\Client;
use Prove\HttpClient\Util\UriBuilder;

class Hits extends AbstractApi
{
    /**
     * The team.
     *
     * @var int
     */
    protected $teamId;

    /**
     * Create a new experiments API instance.
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
     * Log an impression for an experiment.
     *
     * @param  string  $experiment
     * @param  string  $participant
     * @return array
     */
    public function impression(string $experiment, string $participant)
    {
        $uri = $this->buildHitsUri();

        return $this->post($uri, [
            'experiment_key' => $experiment,
            'participant_key' => $participant,
        ]);
    }

    /**
     * Log a conversion for an experiment.
     *
     * @param  string  $experiment
     * @param  string  $participant
     * @param  string  $event
     * @param  array  $attributes
     * @return array
     */
    public function conversion(string $experiment, string $participant, string $event, array $attributes = [])
    {
        $uri = $this->buildHitsUri();

        return $this->post($uri, [
            'experiment_key' => $experiment,
            'participant_key' => $participant,
            'event_key' => $event,
            'event_attributes' => $attributes,
        ]);
    }

    /**
     * Build the hits URI from the given parts.
     *
     * @param  string  ...$parts
     * @return string
     */
    protected function buildHitsUri(string ...$parts)
    {
        return UriBuilder::build(
            'teams', $this->teamId,
            'hit', ...$parts
        );
    }
}
