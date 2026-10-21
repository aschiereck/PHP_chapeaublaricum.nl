<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;


class Locale
{
    const NL = 'nl';
    const DE = 'de';
    const FR = 'fr';
    const EN = 'en';

    public static function getList() : array
    {
        return [
            self::NL => self::NL,
            self::DE => self::DE,
            self::FR => self::FR,
            self::EN => self::EN
        ];
    }
}
