<?php

namespace Checkmango\Api;

use Checkmango\Client;

abstract class AbstractTeamsApi extends AbstractApi
{
    /**
     * The team id.
     *
     * @var int|null
     */
    protected $teamId;

    /**
     * Create a new abstract teams api instance.
     *
     * @param  \Checkmango\Client  $client
     * @param  int  $teamId
     */
    public function __construct(Client $client, int $teamId)
    {
        parent::__construct($client);
        $this->teamId = $teamId;
    }
}
