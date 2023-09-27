<?php

namespace Checkmango\Api\Experiments;

use Checkmango\HttpClient\Util\UriBuilder;

class Variants extends AbstractExperimentsApi
{
    /**
     * List all variants.
     *
     * @return array
     */
    public function list(array $params = [])
    {
        $uri = $this->buildVariantsUri();

        return $this->get($uri, $params);
    }

    /**
     * @return array
     */
    public function create(array $params = [])
    {
        $uri = $this->buildVariantsUri();

        return $this->post($uri, $params);
    }

    /**
     * @return array
     */
    public function show(string $variant, array $params = [])
    {
        $uri = $this->buildVariantsUri($variant);

        return $this->get($uri, $params);
    }

    /**
     * @return array
     */
    public function update(string $variant, array $params = [])
    {
        $uri = $this->buildVariantsUri($variant);

        return $this->put($uri, $params);
    }

    /**
     * @return array
     */
    public function remove(string $variant, array $params = [])
    {
        $uri = $this->buildVariantsUri($variant);

        return $this->delete($uri, $params);
    }

    /**
     * Log an impression for a variant.
     */
    public function impression(string $variant, string $participant, array $params = [])
    {
        $uri = $this->buildVariantsUri($variant, 'impressions');

        return $this->post($uri, array_merge($params, [
            'participant' => $participant,
        ]));
    }

    /**
     * Build the variants URI from the given parts.
     *
     * @return string
     */
    protected function buildVariantsUri(string ...$parts)
    {
        return UriBuilder::build(
            'teams', $this->teamId,
            'experiments', $this->experiment,
            'variants',
            ...$parts
        );
    }
}
