<?php

namespace App\Config;

use App\Config\Section\Evolved;

interface IEvolvedConfigProvider
{
    public function getEvolvedConfig(): Evolved;
}