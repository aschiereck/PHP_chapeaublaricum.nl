<?php

namespace App\Model\Image;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class TeamMemberImage extends AbstractImage
{
    const MODEL = 'TeamMemberImage';  //corresponds to annotation in AbstractImage

    public function getModelName()
    {
        return self::MODEL;
    }
}

