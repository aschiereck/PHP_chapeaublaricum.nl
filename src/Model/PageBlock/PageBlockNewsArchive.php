<?php

namespace App\Model\PageBlock;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockNewsArchive extends AbstractPageBlock
{
    const TEMPLATE = 'news-archive';

    /**
     * PageBlockCarousel constructor.
     */
    public function __construct()
    {
        $this->setTemplate(self::TEMPLATE);
    }

    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();
        return $invalidFields;
    }


    /***************************************************************************************
     * Getters / Setters
     ***************************************************************************************/

    /**
     * @return string
     */
    public function getEditorTitle()
    {
        return '';
    }
}