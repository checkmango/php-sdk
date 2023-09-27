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
     */
    public function __construct(Client $client, int $teamId, string $participant)
    {
        parent::__construct($client);

        $this->teamId = $teamId;
        $this->participant = $participant;
    }
}
