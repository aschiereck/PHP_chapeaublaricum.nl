<?php

namespace App\Model\PageBlock;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockRecentArticles extends AbstractPageBlock
{
    const TEMPLATE = 'recent-articles';

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $numberOfArticles = 2;

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
        return $this->getNumberOfArticles();
    }

    /**
     * @return int
     */
    public function getNumberOfArticles()
    {
        return $this->numberOfArticles;
    }

    /**
     * @param int $numberOfArticles
     * @return PageBlockRecentArticles
     */
    public function setNumberOfArticles($numberOfArticles)
    {
        $this->numberOfArticles = $numberOfArticles;
        return $this;
    }
}