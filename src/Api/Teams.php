<?php

namespace Checkmango\Api;

use Checkmango\HttpClient\Util\UriBuilder;

class Teams extends AbstractTeamsApi
{
    /**
     * List all teams that the authenticated user is part of.
     *
     * @return array
     */
    public function list(array $params = [])
    {
        $uri = UriBuilder::appendSeparator($this->buildTeamsUri());

        return $this->get($uri, $params);
    }

    /**
     * Show a team.
     *
     * @return array
     */
    public function show(int $teamId, array $params = [])
    {
        $uri = $this->buildTeamsUri($teamId);

        return $this->get($uri, $params);
    }

    public function experiments(): Experiments
    {
        return new Experiments($this->getClient(), $this->teamId);
    }

    public function events(): Events
    {
        return new Events($this->getClient(), $this->teamId);
    }

    /**
     * @return \Checkmango\Api\ingest
     */
    public function ingest(): Ingest
    {
        return new Ingest($this->getClient(), $this->teamId);
    }

    public function participants(): Participants
    {
        return new Participants($this->getClient(), $this->teamId);
    }

    /**
     * Build the teams URI from the given parts.
     *
     * @return string
     */
    protected function buildTeamsUri(string ...$parts)
    {
        return UriBuilder::build('teams', ...$parts);
    }
}
