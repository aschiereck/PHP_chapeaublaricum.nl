<?php
namespace App\Util\Encryption;

interface IPasswordEncryption
{
    /**
     * @param string $plainPassword
     * @return string
     */
    public function encrypt($plainPassword);

    /**
     * @param string $plainPassword
     * @param string $hashedPassword
     * @return bool
     */
    public function isMatch($plainPassword, $hashedPassword);
}