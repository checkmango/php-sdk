<?php

namespace Prove\Api\Participants;

use Prove\Api\AbstractApi;
use Prove\Client;

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
     * @param  \Prove\Client  $client
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
