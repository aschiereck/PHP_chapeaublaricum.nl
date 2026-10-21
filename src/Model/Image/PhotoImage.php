<?php

namespace App\Model\Image;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class PhotoImage extends AbstractImage
{
    const MODEL = 'PhotoImage';  //corresponds to annotation in AbstractImage

    public function getModelName()
    {
        return self::MODEL;
    }
}

