<?php

namespace App\Config\Section;

class Evolved
{
    /**
     * @var string
     */
    private $version;
    /**
     * @var string
     */
    private $api;

    /**
     * Evolved constructor.
     * @param string $version
     * @param string $api
     */
    public function __construct(string $version, string $api)
    {
        $this->version = $version;
        $this->api = $api;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->api;
    }
}