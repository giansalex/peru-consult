<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:15 PM.
 */

namespace Peru\Sunat;

use Peru\Http\ClientInterface;
use Peru\Services\RucInterface;

/**
 * Class Ruc.
 */
class Ruc implements RucInterface
{
    public $urlConsult = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
    public $urlRandom = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random';

    /**
     * @var ClientInterface
     */
    public $client;
    /**
     * @var RucParser
     */
    private $parser;

    /**
     * Ruc constructor.
     *
     * @param ClientInterface $client
     * @param RucParser       $parser
     */
    public function __construct(ClientInterface $client, RucParser $parser)
    {
        $this->client = $client;
        $this->parser = $parser;
    }

    /**
     * Get Company Information by RUC.
     *
     * @param string $ruc
     *
     * @return null|Company
     */
    public function get(string $ruc): ?Company
    {
        $random = $this->client->get($this->urlRandom);
        $html = $this->client->get($this->urlConsult."?accion=consPorRuc&nroRuc=$ruc&numRnd=$random");

        return $this->parser->parse($html);
    }
}
