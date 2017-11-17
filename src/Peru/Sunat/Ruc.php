<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:15 PM
 */

namespace Peru\Sunat;

use Peru\CookieRequest;

/**
 * Class Ruc
 * @package Peru\Sunat
 */
class Ruc extends CookieRequest
{
    const URL_CONSULT = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
    const URL_RANDOM = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random';
    private $error;

    /**
     * @param string $ruc
     * @return bool|Company
     */
    public function get($ruc)
    {
        if (strlen($ruc) !== 11) {
            $this->error = 'Ruc debe tener 11 dígitos';
            return false;
        }
        $random = $this->getRandom();
        $url = self::URL_CONSULT . "?accion=consPorRuc&nroRuc=$ruc&numRnd=$random&tipdoc=";

        $req = $this->getCurl();
        $html = $req->get($url);

        if ($req->error) {
            $this->error = $req->errorMessage;
            return false;
        }

        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
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
            /**@var $item \DOMNode */
            if ($item->nodeType != XML_ELEMENT_NODE && $item->nodeName != "tr") continue;
            $i = 0;
            foreach ($item->childNodes as $item2) {
                /**@var $item2 \DOMNode */
                if ($item2->nodeType != XML_ELEMENT_NODE && $item2->nodeName != "td") continue;
                $i++;
                if ($i == 1) {
                    $temp = trim($item2->textContent);
                } else {
                    $select = $xp->query('./select', $item2);
                    if ($select->length > 0) {
                        $arr = [];
                        $options = $select->item(0)->childNodes;
                        foreach ($options as $opt) {
                            /**@var $opt \DOMNode */
                            if ($opt->nodeName != 'option') continue;
                            $arr[] =  trim($opt->textContent);
                        }
                        $dic[$temp] = $arr;
                    } else {
                        $dic[$temp] = trim($item2->textContent);
                    }
                    $i = 0;
                }
            }
        }

        return $this->getCompany($dic);
    }

    public function getError()
    {
        return $this->error;
    }
    private function clearComment(\DOMNode $node)
    {
        $childs = $node->childNodes;
        if (!$childs || !$childs->length) {
            return;
        }

        foreach ($childs as $child) {
            /**@var $child \DOMNode*/
            if ($child->nodeType == XML_COMMENT_NODE) {
                $child->parentNode->removeChild($child);
            } elseif ($child->nodeType == XML_ELEMENT_NODE) {
                $this->clearComment($child);
            }
        }
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
        $rucText  = $items['Número de RUC:'];
        $pos = strpos($rucText,'-');

        $cp->ruc = trim(substr($rucText, 0, $pos));
        $cp->razonSocial = trim(substr($rucText, $pos));
        $cp->nombreComercial = $items['Nombre Comercial:'];
        $cp->tipo = $items['Tipo Contribuyente:'];
        $cp->estado = $items['Estado del Contribuyente:'];
        $cp->condicion = $items['Condición del Contribuyente:'];
        $cp->direccion = preg_replace("[\s+]"," ", $items['Dirección del Domicilio Fiscal:']);
        $cp->fechaInscripcion = $items['Fecha de Inscripción:'];
        $cp->sistEmsion = $items['Sistema de Emisión de Comprobante:'];
        $cp->sistContabilidad = $items['Sistema de Contabilidad:'];
        $cp->actExterior = $items['Actividad de Comercio Exterior:'];
        $cp->actEconomicas = $items['Actividad(es) Económica(s):'];
        $cp->cpPago = $items['Comprobantes de Pago c/aut. de impresión (F. 806 u 816):'];
        $cp->sistElectronica = $items['Sistema de Emision Electronica:'];
        $cp->fechaEmisorFe = $items['Emisor electrónico desde:'];

        $cpText = $items['Comprobantes Electrónicos:'];
        $cpes = [];
        if ($cpText != '-') {
            $cpes = explode(',', $cpText);
        }
        $cp->cpeElectronico = $cpes;
        $cp->fechaPle = $items['Afiliado al PLE desde:'];
        $cp->padrones = $items['Padrones :'];

        return $cp;
    }
}