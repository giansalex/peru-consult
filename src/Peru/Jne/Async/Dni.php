<?php

namespace Peru\Jne\Async;

use Peru\Http\Async\ClientInterface;
use Peru\Jne\DniParser;
use React\Promise\PromiseInterface;

class Dni
{
    private const URL_CONSULT = 'https://aplicaciones007.jne.gob.pe/srop_publico/Consulta/api/AfiliadoApi/GetNombresCiudadano';

    /**
     * JNE Request Token
     *
     * @var string
     */
    private $requestToken = 'Dmfiv1Unnsv8I9EoXEzbyQExSD8Q1UY7viyyf_347vRCfO-1xGFvDddaxDAlvm0cZ8XgAKTaWclVFnnsGgoy4aLlBGB5m-E8rGw_ymEcCig1:eq4At-H2zqgXPrPnoiDGFZH0Fdx5a-1UiyVaR4nQlCvYZzAhzmvWxLwkUk6-yORYrBBxEnoG5sm-Hkiyc91so6-nHHxIeLee5p700KE47Cw1';

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
     * @param string $requestToken
     */
    public function setRequestToken(string $requestToken): void
    {
        $this->requestToken = $requestToken;
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
        $url = self::URL_CONSULT;
        $payload = json_encode(['CODDNI' => $dni]);

        return $this->client
            ->postAsync(
                $url,
                $payload,
                [
                    'Content-Type' => 'application/json;chartset=utf-8',
                    'Content-Length' => strlen($payload),
                    'Requestverificationtoken' => $this->requestToken,
                ])
            ->then(function ($json) use ($dni) {
                $result = json_decode($json);
                if (!$result || !isset($result->data)) {
                    return null;
                }

                return $this->parser->parse($dni, $result->data);
            });
    }
}
