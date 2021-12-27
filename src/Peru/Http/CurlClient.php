<?php

namespace Peru\Http;

class CurlClient implements ClientInterface
{
    private const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.72 Safari/537.36';
    private $ch;

    public function __construct()
    {
        $this->ch = curl_init();
    }

    public function get(string $url, array $headers = [])
    {
        $this->setDefaultConfig($url, $headers);
        curl_setopt($this->ch, CURLOPT_POST, 0);

        return curl_exec($this->ch);
    }

    public function post(string $url, $data, array $headers = [])
    {
        $raw = is_array($data) ? http_build_query($data) : $data;

        $this->setDefaultConfig($url, $headers);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $raw);

        return curl_exec($this->ch);
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }

    private function setDefaultConfig(string $url, array $headers): void
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, '/dev/null');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->buildHeaders($headers));
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    }

    private function buildHeaders(array $headers): array {
        $formatHeaders = [];
        foreach ($headers as $key => $value) {
            $formatHeaders[] = "$key: $value";
        }

        return $formatHeaders;
    }
}