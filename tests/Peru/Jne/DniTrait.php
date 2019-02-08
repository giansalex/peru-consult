<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 16/01/2018
 * Time: 19:37
 */

namespace Tests\Peru\Jne;

use Peru\Http\ClientInterface;

/**
 * Trait DniTrait
 * @method \PHPUnit\Framework\MockObject\MockBuilder getMockBuilder(string $className)
 */
trait DniTrait
{
    /**
     * @return ClientInterface
     */
    private function getClientMock()
    {
        $stub = $this->getHttpMock(function () {
            return false;
        });

        /**@var $stub ClientInterface*/
        return $stub;
    }

    private function getHttpMock(callable $callable)
    {
        $stub = $this->getMockBuilder(ClientInterface::class)
            ->getMock();

        $stub->method('get')
            ->willReturnCallback($callable);

        return $stub;

    }
}