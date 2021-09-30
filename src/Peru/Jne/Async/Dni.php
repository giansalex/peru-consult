<?php

namespace Peru\Jne\Async;

use Peru\Http\Async\ClientInterface;
use Peru\Jne\DniParser;
use Peru\Jne\Endpoints;
use React\Promise\PromiseInterface;

class Dni
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var DniParser
     */
    private $parser;

    /**
     * Dni constructor.
     *
     * @param ClientInterface $client
     * @param DniParser       $parser
     */
    public function __construct(ClientInterface $client, DniParser $parser)
    {
        $this->client = $client;
        $this->parser = $parser;
    }

    /**
     * Override JNE Request token
     *
     * @deprecated unused
     * @param string $requestToken
     */
    public function setRequestToken(string $requestToken): void
    {
    }

    /**
     * Get Person Information by DNI.
     *
     * @param string $dni
     *
     * @return PromiseInterface
     */
    public function get(string $dni): PromiseInterface
    {
        $url = sprintf(Endpoints::CONSULT, $dni);

        return $this->client
            ->postAsync($url, null)
            ->then(function ($json) use ($dni) {
                if ($json === false || !($result = json_decode($json)) || !isset($result->nombreSoli)) {
                    return null;
                }

                return $this->parser->parse($dni, $result);
            });
    }
}
