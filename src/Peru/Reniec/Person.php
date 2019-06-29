<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:16 PM.
 */

namespace Peru\Reniec;

use JsonSerializable;

/**
 * Class Person.
 */
class Person implements JsonSerializable
{
    /**
     * @var string
     */
    public $dni;
    /**
     * @var string
     */
    public $nombres;
    /**
     * @var string
     */
    public $apellidoPaterno;
    /**
     * @var string
     */
    public $apellidoMaterno;
    /**
     * @var string
     */
    public $codVerifica;

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
