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
    private const PATTERN_RANDOM = '/<input type="hidden" name="numRnd" value="(.*)">/';

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
        $random = $this->getRandom();

        $html = $this->client->get(Endpoints::CONSULT."?accion=consPorRuc&nroRuc=$ruc&numRnd=$random&actReturn=1&modo=1");

        return $html === false ? null : $this->parser->parse($html);
    }

    public function getRandom(): ?string
    {
        $html = $this->client->get(Endpoints::RANDOM_PAGE);
        preg_match_all(self::PATTERN_RANDOM, $html, $matches, PREG_SET_ORDER);

        return count($matches) > 0 ? $matches[0][1] : '';
    }
}
