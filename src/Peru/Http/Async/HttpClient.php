<?php

namespace Peru\Http\Async;

use React\HttpClient\Client;
use React\HttpClient\Response;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;

/**
 * Class HttpClient.
 *
 * HttpClient based on ReactPHP
 */
class HttpClient extends Client implements ClientInterface
{
    /**
     * @var array
     */
    public $cookies;

    /**
     * Make GET Request.
     *
     * @param string $url
     * @param array  $headers
     *
     * @return PromiseInterface
     */
    public function getAsync(string $url, array $headers = []): PromiseInterface
    {
        $deferred = new Deferred();
        $headers = $this->buildHeaders();
        $request = $this->request('GET', $url, $headers);

        $request->on('response', function (Response $response) use ($deferred) {
            $this->saveCookies($response->getHeaders());

            $result = '';
            $response->on('data', function ($data) use (&$result) {
                $result .= $data;
            });

            $response->on('end', function () use (&$result, $deferred) {
                $deferred->resolve($result);
            });
        });
        $request->on('error', function ($e) use ($deferred) {
            $deferred->reject($e);
        });
        $request->end();

        return $deferred->promise();
    }

    private function saveCookies(array $headers)
    {
        if (!isset($headers['Set-Cookie'])) {
            return;
        }
        $responseCookies = $headers['Set-Cookie'];

        $this->cookies = array_map(function ($cookie) {
            $pos = strpos($cookie, ';');

            return substr($cookie, 0, $pos);
        }, $responseCookies);
    }

    private function buildHeaders()
    {
        if (empty($this->cookies)) {
            return [];
        }

        return ['Cookie' => implode('; ', $this->cookies)];
    }
}
