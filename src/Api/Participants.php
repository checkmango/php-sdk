<?php

namespace Checkmango\Api;

use Checkmango\Api\Participants\Experiments;
use Checkmango\HttpClient\Util\UriBuilder;

class Participants extends AbstractTeamsApi
{
    /**
     * List all participants.
     *
     * @return array
     */
    public function list(array $params = [])
    {
        $uri = UriBuilder::appendSeparator($this->buildParticipantsUri());

        return $this->get($uri, $params);
    }

    /**
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
     * @return array
     */
    public function update(string $participant, array $params = [])
    {
        $uri = $this->buildParticipantsUri($participant);

        return $this->put($uri, $params);
    }

    /**
     * Get the participants experiment API.
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
