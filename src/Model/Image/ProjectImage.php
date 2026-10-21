<?php

namespace App\Model\Image;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class ProjectImage extends AbstractImage
{
    const MODEL = 'ProjectImage';  //corresponds to annotation in AbstractImage

    public function getModelName()
    {
        return self::MODEL;
    }
}

