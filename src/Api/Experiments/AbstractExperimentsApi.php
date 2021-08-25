<?php

namespace Prove\Api\Experiments;

use Prove\Api\AbstractApi;
use Prove\Client;

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
     *
     * @param  \Prove\Client  $client
     * @param  int  $teamId
     * @param  string  $experiment
     */
    public function __construct(Client $client, int $teamId, string $experiment)
    {
        parent::__construct($client);

        $this->teamId = $teamId;
        $this->experiment = $experiment;
    }
}
