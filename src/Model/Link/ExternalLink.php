<?php

namespace App\Model\Link;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class ExternalLink extends AbstractLink
{
    const TYPE = 'external';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $url = '';

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return ExternalLink
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }
}

