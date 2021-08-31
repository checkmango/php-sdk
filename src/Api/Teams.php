<?php

namespace Prove\Api;

use Prove\HttpClient\Util\UriBuilder;

class Teams extends AbstractTeamsApi
{
    /**
     * List all teams that the authenticated user is part of.
     *
     * @param  array  $params
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
     * @param  int  $teamId
     * @param  array  $params
     * @return array
     */
    public function show(int $teamId, array $params = [])
    {
        $uri = $this->buildTeamsUri($teamId);

        return $this->get($uri, $params);
    }

    /**
     * @return \Prove\Api\Experiments
     */
    public function experiments(): Experiments
    {
        return new Experiments($this->getClient(), $this->teamId);
    }

    /**
     * @return \Prove\Api\Events
     */
    public function events(): Events
    {
        return new Events($this->getClient(), $this->teamId);
    }

    /**
     * @return \Prove\Api\Hits
     */
    public function hits(): Hits
    {
        return new Hits($this->getClient(), $this->teamId);
    }

    /**
     * @return \Prove\Api\Participants
     */
    public function participants(): Participants
    {
        return new Participants($this->getClient(), $this->teamId);
    }

    /**
     * Build the teams URI from the given parts.
     *
     * @param string ...$parts
     * @return string
     */
    protected function buildTeamsUri(string ...$parts)
    {
        return UriBuilder::build('teams', ...$parts);
    }
}
