<?php

namespace Peru\Http;

use GuzzleHttp\Client;

class GuzzleClient implements ClientInterface
{
    private const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.72 Safari/537.36';

    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'cookies' => true,
            'timeout' => 60,
        ]);
    }

    public function get(string $url, array $headers = [])
    {
        return $this->client->get($url, [
            'headers' => array_merge([
                'User-Agent' => self::USER_AGENT,
            ], $headers)
        ])->getBody()->getContents();
    }

    public function post(string $url, $data, array $headers = [])
    {
        return $this->client->post($url, [
            'headers' => array_merge([
                'User-Agent' => self::USER_AGENT,
            ], $headers),
            'form_params' => $data
        ])->getBody()->getContents();
    }
}