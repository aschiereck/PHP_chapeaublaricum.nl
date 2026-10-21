<?php

namespace App\Util\Http;

class HttpClientFactory implements IHttpClientFactory
{
    /**
     * @param $url
     * @return IHttpClient
     */
    public function createClient($url)
    {
        $client = new HttpClient(false, $url);
        return $client;
    }
}