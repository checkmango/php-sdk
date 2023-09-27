<?php

namespace Checkmango\Api\Experiments;

use Checkmango\Api\AbstractApi;
use Checkmango\Client;

abstract class AbstractExperimentsApi extends AbstractApi
{
    /**
     * The team id.
     *
     * @var int
     */
    protected $teamId;

    /**
     * The experiment key.
     *
     * @var string
     */
    protected $experiment;

    /**
     * Create a new API instance.
     */
    public function __construct(Client $client, int $teamId, string $experiment)
    {
        parent::__construct($client);

        $this->teamId = $teamId;
        $this->experiment = $experiment;
    }
}
