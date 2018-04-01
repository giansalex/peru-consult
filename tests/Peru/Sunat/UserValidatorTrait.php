<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 01/04/2018
 * Time: 09:35
 */

namespace Tests\Peru\Sunat;

use Peru\Http\ClientInterface;

/**
 * Trait UserValidatorTrait
 * @method \PHPUnit_Framework_MockObject_MockBuilder getMockBuilder(string $className)
 * @method \PHPUnit_Framework_MockObject_Stub_Return returnValue(mixed $value)
 */
trait UserValidatorTrait
{
    /**
     * @param bool $success
     * @return ClientInterface
     */
    private function getClientMock($success)
    {
        $stub =  $this->getMockBuilder(ClientInterface::class)
                    ->getMock();

        $stub->method('post')
            ->willReturnCallback(function () use ($success) {
                $path = $success
                    ? __DIR__.'/../../Resources/validez_correct.html'
                    : __DIR__.'/../../Resources/validez_incorrect.html';


                return file_get_contents($path);
            });

        /**@var $stub ClientInterface*/
        return $stub;
    }
}