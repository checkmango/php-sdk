<?php

namespace Checkmango;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Discovery\Psr17FactoryDiscovery;
use Checkmango\Api\Teams;
use Checkmango\HttpClient\Builder;
use Checkmango\HttpClient\Message\ResponseMediator;
use Checkmango\HttpClient\Plugin\Authentication;
use Checkmango\HttpClient\Plugin\ExceptionThrower;
use Checkmango\HttpClient\Plugin\History;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * The default base URL.
     *
     * @var string
     */
    private const BASE_URL = 'https://checkmango.com';

    /**
     * The default user agent header.
     *
     * @var string
     */
    private const USER_AGENT = 'checkmango-php-api-client/1.0';

    /**
     * The HTTP client builder.
     *
     * @var \Checkmango\HttpClient\Builder
     */
    private $httpClientBuilder;

    /**
     * The response history plugin.
     *
     * @var \Checkmango\HttpClient\Plugin\History
     */
    private $responseHistory;

    /**
     * Create a new Checkmango API client instance.
     *
     * @param  \Checkmango\HttpClient\Builder|null  $httpClientBuilder
     * @param  string|null  $baseUrl
     */
    public function __construct(Builder $httpClientBuilder = null, string $baseUrl = null)
    {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();
        $this->responseHistory = new History();

        $builder->addPlugin(new ExceptionThrower());
        $builder->addPlugin(new HistoryPlugin($this->responseHistory));
        $builder->addPlugin(new RedirectPlugin());

        $builder->addPlugin(new HeaderDefaultsPlugin([
            'Accept' => ResponseMediator::JSON_CONTENT_TYPE,
            'User-Agent' => self::USER_AGENT,
        ]));

        $this->setUrl($baseUrl ?? self::BASE_URL);
    }

    /**
     * Create a Checkmango Client instance.
     *
     * @param  \Psr\Http\Client\ClientInterface  $httpClient
     * @return static
     */
    public static function createWithHttpClient(ClientInterface $httpClient): self
    {
        $builder = new Builder($httpClient);

        return new self($builder);
    }

    /**
     * @param  int|null  $teamId
     * @return \Checkmango\Api\Teams
     */
    public function teams(int $teamId = null): Teams
    {
        return new Teams($this, $teamId);
    }

    /**
     * Authenticate a user for all requests.
     *
     * @param  string  $token
     * @return void
     */
    public function authenticate(string $token): void
    {
        $this->getHttpClientBuilder()->removePlugin(Authentication::class);
        $this->getHttpClientBuilder()->addPlugin(new Authentication($token));
    }

    /**
     * Set the base URL.
     *
     * @param  string  $url
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->httpClientBuilder->removePlugin(AddHostPlugin::class);
        $this->httpClientBuilder->addPlugin(new AddHostPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri($url)));
    }

    /**
     * Get the HTTP client.
     *
     * @return \Http\Client\Common\HttpMethodsClientInterface
     */
    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * Get the HTTP client builder.
     *
     * @return \Checkmango\HttpClient\Builder
     */
    protected function getHttpClientBuilder(): Builder
    {
        return $this->httpClientBuilder;
    }

    /**
     * Get the last response.
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->responseHistory->getLastResponse();
    }
}
