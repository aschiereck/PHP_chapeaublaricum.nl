<?php

namespace App\Model\PageBlock;

use App\Model\Carousel;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockCarousel extends AbstractPageBlock
{
    const TEMPLATE = 'carousel';

    /**
     * @var Carousel
     * @ORM\ManyToOne(targetEntity="\App\Model\Carousel", inversedBy="pageBlocks")
     */
    private $carousel;

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
     * @return Carousel
     */
    public function getCarousel()
    {
        return $this->carousel;
    }

    /**
     * @param Carousel $carousel
     * @return PageBlockCarousel
     */
    public function setCarousel($carousel)
    {
        $this->carousel = $carousel;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditorTitle()
    {
        return $this->carousel ? $this->carousel->getName() : '';
    }
}