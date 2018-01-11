<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:15 PM.
 */

namespace Peru\Sunat;

use Peru\CookieRequest;

/**
 * Class Ruc.
 */
class Ruc extends CookieRequest
{
    const URL_CONSULT = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
    const URL_RANDOM = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random';
    private $error;

    /**
     * @param string $ruc
     *
     * @return bool|Company
     */
    public function get($ruc)
    {
        if (strlen($ruc) !== 11) {
            $this->error = 'Ruc debe tener 11 dígitos';

            return false;
        }
        $random = $this->getRandom();
        $url = self::URL_CONSULT."?accion=consPorRuc&nroRuc=$ruc&numRnd=$random&tipdoc=";

        $req = $this->getCurl();
        $html = $req->get($url);

        if ($req->error) {
            $this->error = $req->errorMessage;

            return false;
        }

        $dic = $this->parseHtml($html);
        if ($dic === false) {

            return false;
        }

        return $this->getCompany($dic);
    }

    /**
     * Get Last error message.
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $html
     * @return array|bool
     */
    private function parseHtml($html)
    {
        $dom = new \DOMDocument();
        $prevState = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($prevState);

        $xp = new \DOMXPath($dom);

        $table = $xp->query('./html/body/table[1]');

        if ($table->length == 0) {
            $this->error = 'No se encontro el ruc';

            return false;
        }
        $nodes = $table->item(0)->childNodes;
        $dic = [];

        $temp = '';
        foreach ($nodes as $item) {
            /** @var $item \DOMNode */
            if ($item->nodeType != XML_ELEMENT_NODE && $item->nodeName != 'tr') {
                continue;
            }
            $i = 0;
            foreach ($item->childNodes as $item2) {
                /** @var $item2 \DOMNode */
                if ($item2->nodeType != XML_ELEMENT_NODE && $item2->nodeName != 'td') {
                    continue;
                }
                ++$i;
                if ($i == 1) {
                    $temp = trim($item2->textContent);
                } else {
                    $select = $xp->query('./select', $item2);
                    if ($select->length > 0) {
                        $arr = [];
                        $options = $select->item(0)->childNodes;
                        foreach ($options as $opt) {
                            /** @var $opt \DOMNode */
                            if ($opt->nodeName != 'option') {
                                continue;
                            }
                            $arr[] = trim($opt->textContent);
                        }
                        $dic[$temp] = $arr;
                    } else {
                        $dic[$temp] = trim($item2->textContent);
                    }
                    $i = 0;
                }
            }
        }

        $dic['Phone'] = $this->getPhone($html);

        return $dic;
    }

    private function getRandom()
    {
        $curl = $this->getCurl();
        $code = $curl->get(self::URL_RANDOM);

        if ($curl->error) {
            return false;
        }

        return $code;
    }

    private function getCompany(array $items)
    {
        $cp = new Company();
        $rucText = $items['Número de RUC:'];
        $pos = strpos($rucText, '-');

        $cp->ruc = trim(substr($rucText, 0, $pos));
        $cp->razonSocial = trim(substr($rucText, $pos + 1));
        $cp->nombreComercial = $items['Nombre Comercial:'];
        $cp->telefonos = $items['Phone'];
        $cp->tipo = $items['Tipo Contribuyente:'];
        $cp->estado = $items['Estado del Contribuyente:'];
        $cp->condicion = $items['Condición del Contribuyente:'];

        $cp->direccion = $items['Dirección del Domicilio Fiscal:'];
        $cp->fechaInscripcion = $this->parseDate($items['Fecha de Inscripción:']);
        $cp->sistEmsion = $items['Sistema de Emisión de Comprobante:'];
        $cp->sistContabilidad = $items['Sistema de Contabilidad:'];
        $cp->actExterior = $items['Actividad de Comercio Exterior:'];
        $cp->actEconomicas = $items['Actividad(es) Económica(s):'];
        $cp->cpPago = $items['Comprobantes de Pago c/aut. de impresión (F. 806 u 816):'];
        $cp->sistElectronica = $items['Sistema de Emision Electronica:'];
        $cp->fechaEmisorFe = $this->parseDate($items['Emisor electrónico desde:']);
        $cpText = $items['Comprobantes Electrónicos:'];
        $cpes = [];
        if ($cpText != '-') {
            $cpes = explode(',', $cpText);
        }
        $cp->cpeElectronico = $cpes;
        $cp->fechaPle = $this->parseDate($items['Afiliado al PLE desde:']);
        $cp->padrones = $items['Padrones :'];
        if ($cp->sistElectronica == '-') {
            $cp->sistElectronica = [];
        }

        $this->fixDirection($cp);
        return $cp;
    }

    private function getPhone($html)
    {
        $arr = [];
        $patron = '/<td class="bgn" colspan=1>Tel&eacute;fono\(s\):<\/td>[ ]*-->\r\n<!--\t[ ]*<td class="bg" colspan=1>(.*)<\/td>/';
        preg_match_all($patron, $html, $matches, PREG_SET_ORDER);
        if (count($matches) > 0) {
            $phones = explode('/', $matches[0][1]);
            foreach ($phones as $phone) {
                if (empty($phone)) {
                    continue;
                }
                $arr[] = trim($phone);
            }
        }

        return $arr;
    }

    /**
     * @param $text
     * @return null|string
     */
    private function parseDate($text)
    {
        if (empty($text) || $text == '-') {
            return null;
        }

        $date = \DateTime::createFromFormat('d/m/Y', $text);

        return $date === false ? null : $date->format('Y-m-d').'T00:00:00.000Z';
    }

    private function fixDirection(Company $company)
    {
        $items = explode('                                               -', $company->direccion);
        if (count($items) == 3) {
            $pieces = explode(' ', trim($items[0]));
            $company->departamento = $this->fixDepartamento(array_pop($pieces));
            $company->provincia = trim($items[1]);
            $company->distrito = trim($items[2]);
        }

        $company->direccion = preg_replace("[\s+]", ' ', $company->direccion);
    }

    private function fixDepartamento($department)
    {
        $department = strtoupper($department);
        switch ($department)
        {
            case 'DIOS': return 'MADRE DE DIOS';
            case 'MARTIN': return 'SAN MARTIN';
            case 'LIBERTAD': return 'LA LIBERTAD';
        }

        return $department;
    }
}
