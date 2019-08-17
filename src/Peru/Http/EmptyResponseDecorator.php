<?php

namespace Peru\Http;

class EmptyResponseDecorator implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * EmptyResponseDecorator constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get Request.
     *
     * @param string $url
     * @param array  $headers
     *
     * @return string
     */
    public function get(string $url, array $headers = [])
    {
        $response = $this->client->get($url, $headers);

        return false === $response ? '' : $response;
    }

    /**
     * Post Request.
     *
     * @param string $url
     * @param mixed  $data
     * @param array  $headers
     *
     * @return string
     */
    public function post(string $url, $data, array $headers = [])
    {
        $response = $this->client->post($url, $data, $headers);

        return false === $response ? '' : $response;
    }
}
