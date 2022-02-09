<?php

namespace Checkmango\Api\Participants;

use Checkmango\Api\AbstractApi;
use Checkmango\Client;

abstract class AbstractParticipantsApi extends AbstractApi
{
    /**
     * The team id.
     *
     * @var int
     */
    protected $teamId;

    /**
     * The participant key.
     *
     * @var string
     */
    protected $participant;

    /**
     * Create a new API instance.
     *
     * @param  \Checkmango\Client  $client
     * @param  int  $teamId
     * @param  string  $participant
     */
    public function __construct(Client $client, int $teamId, string $participant)
    {
        parent::__construct($client);

        $this->teamId = $teamId;
        $this->participant = $participant;
    }
}
