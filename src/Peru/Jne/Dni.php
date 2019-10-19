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
     * @return Person|null
     */
    public function get(string $dni): ?Person
    {
        $url = self::URL_CONSULT;
       
        $json = $this->client->post(
            $url, 
            json_encode(['CODDNI' => $dni]),
            [
                'Content-Type' => 'application/json;chartset=utf-8',
                'Requestverificationtoken' => '30OB7qfO2MmL2Kcr1z4S0ttQcQpxH9pDUlZnkJPVgUhZOGBuSbGU4qM83JcSu7DZpZw-IIIfaDZgZ4vDbwE5-L9EPoBIHOOC1aSPi4FS_Sc1:clDOiaq7mKcLTK9YBVGt2R3spEU8LhtXEe_n5VG5VLPfG9UkAQfjL_WT9ZDmCCqtJypoTD26ikncynlMn8fPz_F_Y88WFufli38cUM-24PE1',
            ]);
        $raw = json_decode($json)->data;

        return $this->parser->parse($dni, $raw);
    }
}
