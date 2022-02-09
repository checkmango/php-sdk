<?php

namespace Checkmango\Api\Participants;

use Checkmango\HttpClient\Util\UriBuilder;

class Experiments extends AbstractParticipantsApi
{
    /**
     * List all experiments the participant is part of.
     *
     * @param  array  $params
     * @return array
     */
    public function list(array $params = [])
    {
        $uri = $this->buildExperimentsUri();

        return $this->get($uri, $params);
    }

    /**
     * @param  array  $params
     * @return array
     */
    public function create(array $params = [])
    {
        $uri = $this->buildExperimentsUri();

        return $this->post($uri, $params);
    }

    /**
     * @param  string  $experiment
     * @param  array  $params
     * @return array
     */
    public function show(string $experiment, array $params = [])
    {
        $uri = $this->buildExperimentsUri($experiment);

        return $this->get($uri, $params);
    }

    /**
     * Remove the participant from an experiment.
     *
     * @param  string  $experiment
     * @param  array  $params
     * @return array
     */
    public function remove(string $experiment, array $params = [])
    {
        $uri = $this->buildExperimentsUri($experiment);

        return $this->delete($uri, $params);
    }

    /**
     * Build the experiments URI from the given parts.
     *
     * @param  string  ...$parts
     * @return string
     */
    protected function buildExperimentsUri(string ...$parts)
    {
        return UriBuilder::build(
            'teams', $this->teamId,
            'participants', $this->participant,
            'experiments',
            ...$parts
        );
    }
}
