<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 01/04/2018
 * Time: 09:33.
 */
declare(strict_types=1);

namespace Tests\Peru\Sunat;

use Peru\Http\ContextClient;
use Peru\Sunat\UserValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class UserValidatorTest.
 */
class UserValidatorTest extends TestCase
{
    /**
     * @var UserValidator
     */
    private $validator;

    protected function setUp()
    {
        $this->validator = new UserValidator(new ClientStubDecorator(new ContextClient()));
    }

    public function testValidezCorrect()
    {
        $result = $this->validator->valid('20000000001', 'HUAFDSMU');

        $this->assertTrue($result);
    }

    public function testValidezInCorrect()
    {
        $result = $this->validator->valid('20000000001', 'INVALID');

        $this->assertFalse($result);
    }
}
