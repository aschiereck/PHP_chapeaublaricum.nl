<?php

namespace App\Model\PageBlock;

use App\Model\Slider;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockSlider extends AbstractPageBlock
{
    const TEMPLATE = 'slider';

    /**
     * @var Slider
     * @ORM\ManyToOne(targetEntity="\App\Model\Slider", inversedBy="pageBlocks")
     */
    private $slider;

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
     * @return Slider
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * @param Slider $slider
     * @return PageBlockSlider
     */
    public function setSlider($slider)
    {
        $this->slider = $slider;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditorTitle()
    {
        return $this->slider ? $this->slider->getName() : '';
    }
}