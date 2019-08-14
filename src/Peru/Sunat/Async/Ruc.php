<?php

namespace Peru\Sunat\Async;

use Peru\Http\Async\ClientInterface;
use Peru\Sunat\RucHtmlParser;
use React\Promise\PromiseInterface;

class Ruc
{
    private const URL_CONSULT = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
    private const URL_RANDOM = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RucHtmlParser
     */
    private $parser;

    /**
     * Ruc constructor.
     *
     * @param ClientInterface $client
     * @param RucHtmlParser   $parser
     */
    public function __construct(ClientInterface $client, RucHtmlParser $parser)
    {
        $this->client = $client;
        $this->parser = $parser;
    }

    public function get(string $ruc): PromiseInterface
    {
        $promise = $this->client
            ->getAsync(self::URL_RANDOM)
            ->then(function ($random) use ($ruc) {
                $url = self::URL_CONSULT."?accion=consPorRuc&nroRuc=$ruc&numRnd=$random&tipdoc=";

                return $this->client->getAsync($url);
            })
            ->then(function ($html) {
                return $this->parser->parse($html);
            });

        return $promise;
    }
}
