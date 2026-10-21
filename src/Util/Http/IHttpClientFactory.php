<?php

namespace App\Util\Http;

interface IHttpClientFactory
{
    /**
     * @param $url
     * @return IHttpClient
     */
    public function createClient($url);
}