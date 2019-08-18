<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:17 PM.
 */

namespace Peru\Sunat;

use JsonSerializable;

/**
 * Class Company.
 */
class Company implements JsonSerializable
{
    /**
     * @var string
     */
    public $ruc;
    /**
     * @var string
     */
    public $razonSocial;
    /**
     * @var string
     */
    public $nombreComercial;
    /**
     * @var array
     */
    public $telefonos;
    /**
     * @var string
     */
    public $tipo;
    /**
     * @var string
     */
    public $estado;
    /**
     * @var string
     */
    public $condicion;
    /**
     * @var string
     */
    public $direccion;
    /**
     * @var string
     */
    public $departamento;
    /**
     * @var string
     */
    public $provincia;
    /**
     * @var string
     */
    public $distrito;
    /**
     * @var string
     */
    public $fechaInscripcion;
    /**
     * @var string
     */
    public $sistEmsion;
    /**
     * @var string
     */
    public $sistContabilidad;
    /**
     * @var string
     */
    public $actExterior;
    /**
     * @var array
     */
    public $actEconomicas;
    /**
     * @var array
     */
    public $cpPago;
    /**
     * @var array
     */
    public $sistElectronica;
    /**
     * @var string
     */
    public $fechaEmisorFe;
    /**
     * @var array
     */
    public $cpeElectronico;
    /**
     * @var string
     */
    public $fechaPle;
    /**
     * @var array
     */
    public $padrones;
    /**
     * @var string
     */
    public $fechaBaja;
    /**
     * @var string
     */
    public $profesion;

    /**
     * Specify data which should be serialized to JSON.
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
