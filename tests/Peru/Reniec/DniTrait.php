<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 16/01/2018
 * Time: 19:37
 */

declare(strict_types=1);

namespace Tests\Peru\Reniec;

use Peru\Http\ClientInterface;

/**
 * Trait DniTrait
 * @method \PHPUnit\Framework\MockObject\MockBuilder getMockBuilder(string $className)
 * @method \PHPUnit\Framework\MockObject\Stub\ReturnStub returnValue(mixed $value)
 */
trait DniTrait
{
    /**
     * @param $url
     * @return ClientInterface
     */
    private function getClientMock($url)
    {
        $stub = $this->getHttpMock('get', $url);

        /**@var $stub ClientInterface*/
        return $stub;
    }

    /**
     * @param $url
     * @return ClientInterface
     */
    private function getClientCaptchaMock($url)
    {
        $stub = $this->getHttpMock('post', $url);

        $image = file_get_contents(__DIR__.'/../../Resources/captcha.jpg');
        $stub->method('get')
            ->will($this->returnValue($image));

        /**@var $stub ClientInterface*/
        return $stub;
    }

    private function getHttpMock($method, $url)
    {
        $stub = $this->getMockBuilder(ClientInterface::class)
            ->getMock();
        $stub->method($method)
            ->willReturnCallback(function ($param) use ($url) {
                if (empty($url)) {
                    return '111';
                }
                $count = strlen($url);
                if (substr($param, 0, $count) == $url) {
                    return false;
                }

                return '111';
            });

        return $stub;

    }
}