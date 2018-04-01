<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 01/04/2018
 * Time: 09:33
 */

namespace Tests\Peru\Sunat;
use Peru\Sunat\UserValidator;

/**
 * Class UserValidatorTest
 */
class UserValidatorTest extends \PHPUnit_Framework_TestCase
{
    use UserValidatorTrait;

    public function testValidezCorrect()
    {
        $consulta = new UserValidator($this->getClientMock(true));

        $result = $consulta->vaild('20000000001', 'HUAFDSMU');

        $this->assertTrue($result);
    }

    public function testValidezInCorrect()
    {
        $consulta = new UserValidator($this->getClientMock(false));

        $result = $consulta->vaild('20000000001', 'HUAFDSMU');

        $this->assertFalse($result);
    }
}