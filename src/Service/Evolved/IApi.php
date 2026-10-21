<?php

namespace App\Service\Evolved;


interface IApi
{
    /**
     * @param string $host
     * @return bool
     */
    public function verifyHost($host);

    /**
     * @return BrandingProfile
     */
    public function getBranding();

    /**
     * @param string $token
     * @return bool
     */
    public function validateToken($token);
}