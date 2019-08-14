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
     * Temporal result store.
     *
     * @var string
     */
    private $result;

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

            $this->result = '';
            $response->on('data', function ($data) {
                $this->result .= $data;
            });

            $response->on('end', function () use ($deferred) {
                $deferred->resolve($this->result);
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
        if (is_string($responseCookies)) {
            $responseCookies = [$responseCookies];
        }

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
