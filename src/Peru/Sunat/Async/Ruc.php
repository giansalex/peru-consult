<?php

namespace Peru\Sunat\Async;

use Peru\Http\Async\ClientInterface;
use Peru\Sunat\Endpoints;
use Peru\Sunat\RandomTrait;
use Peru\Sunat\RucParser;
use React\Promise\PromiseInterface;

class Ruc
{
    use RandomTrait;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RucParser
     */
    private $parser;

    /**
     * Ruc constructor.
     *
     * @param ClientInterface $client
     * @param RucParser   $parser
     */
    public function __construct(ClientInterface $client, RucParser $parser)
    {
        $this->client = $client;
        $this->parser = $parser;
    }

    public function get(string $ruc): PromiseInterface
    {
        return $this->client
            ->getAsync(Endpoints::CONSULT)
            ->then(function () {
                $data = [
                    'accion' => 'consPorRazonSoc',
                    'razSoc' => 'BVA FOODS',
                ];

                return $this->client->postAsync(Endpoints::CONSULT,
                    http_build_query($data),
                    [
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ]);
            })
            ->then(function ($htmlRandom) use ($ruc) {
                $random = $this->getRandom($htmlRandom);
                $data = [
                    'accion' => 'consPorRuc',
                    'nroRuc' => $ruc,
                    'numRnd' => $random,
                    'actReturn' => '1',
                    'modo' => '1',
                ];
                return $this->client->postAsync(Endpoints::CONSULT,
                    http_build_query($data),
                    [
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ]);
            })
            ->then(function ($html) {
                return $this->parser->parse($html);
            });
    }
}
