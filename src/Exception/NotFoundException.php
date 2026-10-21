<?php

namespace App\Exception;

use Exception;

class NotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Page not found', 404);
    }
}