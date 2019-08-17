<?php

namespace Peru\Jne\Async;

use Peru\Http\Async\ClientInterface;
use Peru\Jne\DniParser;
use React\Promise\PromiseInterface;

class Dni
{
    private const URL_CONSULT_FORMAT = 'http://aplicaciones007.jne.gob.pe/srop_publico/Consulta/Afiliado/GetNombresCiudadano?DNI=%s';

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
     * Get Person Information by DNI.
     *
     * @param string $dni
     *
     * @return PromiseInterface
     */
    public function get(string $dni): PromiseInterface
    {
        $url = sprintf(self::URL_CONSULT_FORMAT, $dni);

        return $this->client
            ->getAsync($url)
            ->then(function ($raw) use ($dni) {
                return $this->parser->parse($dni, $raw);
            });
    }
}
