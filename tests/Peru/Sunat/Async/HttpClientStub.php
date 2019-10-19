<?php

declare(strict_types=1);

namespace Tests\Peru\Sunat\Async;

use Peru\Http\Async\ClientInterface;
use React\Promise\PromiseInterface;
use Tests\Peru\Sunat\ClientStubDecorator;

class HttpClientStub implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * HttpClientStub constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Make GET Request.
     *
     * @param string $url
     * @param array $headers
     *
     * @return PromiseInterface
     */
    public function getAsync(string $url, array $headers = []): PromiseInterface
    {
        return $this->client->getAsync(ClientStubDecorator::getNewUrl($url), $headers);
    }

    /**
     * Post Request.
     *
     * @param string $url
     * @param mixed $data
     * @param array $headers
     *
     * @return PromiseInterface
     */
    public function postAsync(string $url, $data, array $headers = []): PromiseInterface
    {
        return $this->client->postAsync(ClientStubDecorator::getNewUrl($url), $data, $headers);
    }
}