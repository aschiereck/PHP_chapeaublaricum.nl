<?php

namespace App\Model\PageBlock;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockTeam extends AbstractPageBlock
{
    const TEMPLATE = 'team';

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