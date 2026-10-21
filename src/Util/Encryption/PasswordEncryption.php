<?php

namespace App\Util\Encryption;

class PasswordEncryption implements IPasswordEncryption
{
    const COST = 12;

    /**
     * @param string $plainPassword
     * @return string
     */
    public function encrypt($plainPassword)
    {
        $options = [
            'cost' => self::COST,
        ];
        return password_hash($plainPassword, PASSWORD_DEFAULT, $options);
    }

    /**
     * @param string $plainPassword
     * @param string $hashedPassword
     * @return bool
     */
    public function isMatch($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword);
    }
}