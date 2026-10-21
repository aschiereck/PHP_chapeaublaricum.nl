<?php

namespace App\Model\Image;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class PageBlockImage extends AbstractImage
{
    const MODEL = 'PageBlockImage';  //corresponds to annotation in AbstractImage

    public function getModelName()
    {
        return self::MODEL;
    }
}

