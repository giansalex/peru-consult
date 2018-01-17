<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 16/01/2018
 * Time: 19:45
 */

namespace Tests\Peru\Sunat;

use Peru\Http\ClientInterface;
use Peru\Sunat\Ruc;

/**
 * Trait RucTrait
 * @method \PHPUnit_Framework_MockObject_MockBuilder getMockBuilder(string $className)
 * @method \PHPUnit_Framework_MockObject_Stub_Return returnValue(mixed $value)
 */
trait RucTrait
{
    /**
     * @param $url
     * @return ClientInterface
     */
    private function getClientMock($url)
    {
        $stub = $this->getHttpMock();

        $stub->method('get')
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

        /**@var $stub ClientInterface*/
        return $stub;
    }

    /**
     * @return ClientInterface
     */
    private function getClientHtmlMock()
    {
        $stub = $this->getHttpMock();

        $stub->method('get')
            ->willReturnCallback(function ($param) {
                if ($param == Ruc::URL_RANDOM) {
                    return '-3234111';
                }

                return utf8_decode(
                    file_get_contents(__DIR__.'/../../Resources/sunat.html')
                );
            });

        /**@var $stub ClientInterface*/
        return $stub;
    }

    private function getHttpMock()
    {
        return $this->getMockBuilder(ClientInterface::class)
            ->getMock();
    }
}