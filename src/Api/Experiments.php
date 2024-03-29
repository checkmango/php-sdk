<?php

namespace Checkmango\Api;

use Checkmango\Api\Experiments\Variants;
use Checkmango\Client;
use Checkmango\HttpClient\Util\UriBuilder;

class Experiments extends AbstractApi
{
    /**
     * The team.
     *
     * @var int
     */
    protected $teamId;

    /**
     * Create a new experiments API instance.
     */
    public function __construct(Client $client, int $teamId)
    {
        parent::__construct($client);

        $this->teamId = $teamId;
    }

    /**
     * List experiments.
     *
     * @return array
     */
    public function list(array $params = [])
    {
        $uri = $this->buildExperimentsUri();

        return $this->get($uri, $params);
    }

    /**
     * Show an experiment.
     *
     * @return array
     */
    public function show(string $experiment, array $params = [])
    {
        $uri = $this->buildExperimentsUri($experiment);

        return $this->get($uri, $params);
    }

    /**
     * Create an experiment.
     *
     * @return array
     */
    public function create(string $experiment, array $params = [])
    {
        $uri = $this->buildExperimentsUri($experiment);

        return $this->post($uri, $params);
    }

    /**
     * Delete an experiment.
     *
     * @return array
     */
    public function remove(string $experiment, array $params = [])
    {
        $uri = $this->buildExperimentsUri($experiment);

        return $this->delete($uri, $params);
    }

    /**
     * Update an experiment.
     *
     * @return array
     */
    public function update(string $experiment, array $params = [])
    {
        $uri = $this->buildExperimentsUri($experiment);

        return $this->put($uri, $params);
    }

    /**
     * Enrol a participant into an experiment.
     *
     * @return array
     */
    public function enrol(string $experiment, string $participant, array $params = [])
    {
        $uri = $this->buildExperimentsUri($experiment, 'enroll');

        return $this->post($uri, array_merge($params, ['participant_key' => $participant]));
    }

    /**
     * Alias for "enrol" method.
     *
     * @return array
     */
    public function enroll(string $experiment, string $participant, array $params = [])
    {
        return $this->enrol($experiment, $participant, $params);
    }

    /**
     * Get the variants API.
     */
    public function variants(string $experiment): Variants
    {
        return new Variants(
            $this->getClient(),
            $this->teamId,
            $experiment
        );
    }

    /**
     * Build the experiments URI from the given parts.
     *
     * @return string
     */
    protected function buildExperimentsUri(string ...$parts)
    {
        return UriBuilder::build(
            'teams', $this->teamId,
            'experiments',
            ...$parts
        );
    }
}
