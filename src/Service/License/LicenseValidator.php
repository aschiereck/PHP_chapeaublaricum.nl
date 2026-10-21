<?php

namespace App\Service\License;

use Zend\Crypt\PublicKey\Rsa;

class LicenseValidator
{
    /**
     * @var string
     */
    private $licenseFile;
    /**
     * @var string
     */
    private $publicKey;

    /**
     * LicenseValidator constructor.
     * @param string $licenseFile
     * @param string $publicKey
     */
    public function __construct($licenseFile, $publicKey)
    {
        $this->licenseFile = $licenseFile;
        $this->publicKey = $publicKey;
    }

    /**
     * @return bool|[]
     */
    public function getLicense()
    {
        try
        {
            if(!@is_file($this->licenseFile))
            {
                return false;
            }

            $contents = file_get_contents($this->licenseFile);
            @list($signature, $contents) = @explode('.', $contents, 2);
            $contents = base64_decode($contents);

            $rsa = Rsa::factory(array(
                'public_key'    => $this->publicKey,
                'binary_output' => false
            ));

            if(!$rsa->verify($contents, $signature, $rsa->getOptions()->getPublicKey()))
            {
                return false;
            }

            return @json_decode($contents, true);
        }
        catch(\Exception $e)
        {
            echo $e->getMessage();
            return false;
        }
    }
}