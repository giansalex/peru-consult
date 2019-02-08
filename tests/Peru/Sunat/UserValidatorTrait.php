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
 * @method \PHPUnit\Framework\MockObject\MockBuilder getMockBuilder(string $className)
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