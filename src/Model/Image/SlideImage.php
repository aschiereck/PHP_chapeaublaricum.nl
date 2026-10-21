<?php

namespace App\Model\Image;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class SlideImage extends AbstractImage
{
    const MODEL = 'SlideImage';  //corresponds to annotation in AbstractImage

    public function getModelName()
    {
        return self::MODEL;
    }
}

