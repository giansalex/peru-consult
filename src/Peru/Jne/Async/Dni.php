<?php

namespace Peru\Jne\Async;

use Peru\Http\Async\ClientInterface;
use Peru\Jne\DniParser;
use React\Promise\PromiseInterface;

class Dni
{
    private const URL_CONSULT = 'http://aplicaciones007.jne.gob.pe/srop_publico/Consulta/api/AfiliadoApi/GetNombresCiudadano';

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
        $url = self::URL_CONSULT;
        $payload = json_encode(['CODDNI' => $dni]);

        return $this->client
            ->postAsync(
                $url,
                $payload,
                [
                    'Content-Type' => 'application/json;chartset=utf-8',
                    'Content-Length' => strlen($payload),
                    'Requestverificationtoken' => '30OB7qfO2MmL2Kcr1z4S0ttQcQpxH9pDUlZnkJPVgUhZOGBuSbGU4qM83JcSu7DZpZw-IIIfaDZgZ4vDbwE5-L9EPoBIHOOC1aSPi4FS_Sc1:clDOiaq7mKcLTK9YBVGt2R3spEU8LhtXEe_n5VG5VLPfG9UkAQfjL_WT9ZDmCCqtJypoTD26ikncynlMn8fPz_F_Y88WFufli38cUM-24PE1',
                ])
            ->then(function ($raw) use ($dni) {
                $raw = json_decode($raw)->data;
                return $this->parser->parse($dni, $raw);
            });
    }
}
