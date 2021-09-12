<?php

namespace Prove\Api;

use Prove\Api\Participants\Experiments;
use Prove\HttpClient\Util\UriBuilder;

class Participants extends AbstractTeamsApi
{
    /**
     * List all participants.
     *
     * @param  array  $params
     * @return array
     */
    public function list(array $params = [])
    {
        $uri = UriBuilder::appendSeparator($this->buildParticipantsUri());

        return $this->get($uri, $params);
    }

    /**
     * @param  array  $params
     * @return array
     */
    public function create(array $params = [])
    {
        $uri = $this->buildParticipantsUri();

        return $this->post($uri, $params);
    }

    /**
     * Show a participant.
     *
     * @param  string  $participant
     * @param  array  $params
     * @return array
     */
    public function show(string $participant, array $params = [])
    {
        $uri = $this->buildParticipantsUri($participant);

        return $this->get($uri, $params);
    }

    /**
     * Delete a participant.
     *
     * @param  string  $participant
     * @param  array  $params
     * @return array
     */
    public function remove(string $participant, array $params = [])
    {
        $uri = $this->buildParticipantsUri($participant);

        return $this->delete($uri, $params);
    }

    /**
     * Update a participant.
     *
     * @param  string  $participant
     * @param  array  $params
     * @return array
     */
    public function update(string $participant, array $params = [])
    {
        $uri = $this->buildParticipantsUri($participant);

        return $this->put($uri, $params);
    }

    /**
     * Get the participants experiment API.
     *
     * @param  string  $participant
     * @return \Prove\Api\Participants\Experiments
     */
    public function experiments(string $participant): Experiments
    {
        return new Experiments(
            $this->getClient(),
            $this->teamId,
            $participant
        );
    }

    /**
     * Build the participants URI from the given parts.
     *
     * @param  string  ...$parts
     * @return string
     */
    protected function buildParticipantsUri(string ...$parts)
    {
        return UriBuilder::build(
            'teams', $this->teamId,
            'participants',
            ...$parts
        );
    }
}
