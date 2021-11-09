<?php
/**
 * Created by PhpStorm.
 * User: Soporte
 * Date: 24/09/2018
 * Time: 11:25.
 */

namespace Peru\Jne;

use Peru\Http\ClientInterface;
use Peru\Reniec\Person;
use Peru\Services\DniInterface;
/**
 * Class Dni.
 */
class Dni implements DniInterface
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
     * @deprecated
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
     * @return Person|null
     */
    public function get(string $dni): ?Person
    {
        $url = sprintf(Endpoints::CONSULT, $dni);
        $json = $this->client->post($url, []);

        if ($json === false || !($result = json_decode($json)) || !isset($result->nombreSoli)) {
            return null;
        }

        return $this->parser->parse($dni, $result);
    }
}
