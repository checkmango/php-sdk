<?php

namespace Prove\Api\Experiments;

use Prove\HttpClient\Util\UriBuilder;

class Variants extends AbstractExperimentsApi
{
    /**
     * List all variants.
     *
     * @param  array  $params
     * @return array
     */
    public function list(array $params = [])
    {
        $uri = $this->buildVariantsUri();

        return $this->get($uri, $params);
    }

    /**
     * @param  array  $params
     * @return array
     */
    public function create(array $params = [])
    {
        $uri = $this->buildVariantsUri();

        return $this->post($uri, $params);
    }

    /**
     * @param  string  $variant
     * @param  array  $params
     * @return array
     */
    public function show(string $variant, array $params = [])
    {
        $uri = $this->buildVariantsUri($variant);

        return $this->get($uri, $params);
    }

    /**
     * @param  string  $variant
     * @param  array  $params
     * @return array
     */
    public function update(string $variant, array $params = [])
    {
        $uri = $this->buildVariantsUri($variant);

        return $this->put($uri, $params);
    }

    /**
     * @param  string  $variant
     * @param  array  $params
     * @return array
     */
    public function remove(string $variant, array $params = [])
    {
        $uri = $this->buildVariantsUri($variant);

        return $this->delete($uri, $params);
    }

    /**
     * Log an impression for a variant.
     *
     * @param  string  $variant
     * @param  string  $participant
     * @param  array  $params
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
     * @param  string  ...$parts
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
