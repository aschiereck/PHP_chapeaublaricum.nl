<?php

namespace App\Model;

use App\Model\Image\SlideImage;
use App\Model\Link\AbstractLink;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class Slide extends AbstractEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name = '';

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $summary = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $linkText = '';

    /**
     * @var AbstractLink
     * @ORM\OneToOne(targetEntity="\App\Model\Link\AbstractLink", orphanRemoval=true, cascade={"remove", "persist"})
     * @ORM\JoinColumn(nullable=true,  onDelete="SET NULL")
     */
    private $link;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @var Slider
     * @ORM\ManyToOne(targetEntity="Slider", inversedBy="slides")
     */
    private $slider;

    /**
     * @var SlideImage
     * @ORM\OneToOne(targetEntity="\App\Model\Image\SlideImage", orphanRemoval=true, cascade={"remove"})
     */
    private $slideImage;


    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();

        $nameValidation = new ValidatorChain();
        $nameValidation
            ->attach(new NotEmpty());

        $slideImageValidation = new ValidatorChain();
        $slideImageValidation
            ->attach(new NotEmpty());

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }
        if(!$slideImageValidation->isValid($this->slideImage))
        {
            $invalidFields['slideImage'] = $slideImageValidation->getMessages();
        }

        return $invalidFields;
    }

    #region Getters / setters

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Slide
     */
    public function setName($name)
    {
        $this->name = (string)$name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return Slide
     */
    public function setSummary($summary)
    {
        $this->summary = (string)$summary;
        return $this;
    }

    /**
     * @return boolean
     */
    public function hasLink()
    {
        return !is_null($this->link);
    }

    /**
     * @return string
     */
    public function getLinkText()
    {
        return $this->linkText;
    }

    /**
     * @param string $linkText
     * @return Slide
     */
    public function setLinkText($linkText)
    {
        $this->linkText = (string)$linkText;
        return $this;
    }

    /**
     * @return AbstractLink
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param AbstractLink $link
     * @return Slide
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }


    /**
     * @return Slider
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * @param Slider $slider
     * @return Slide
     */
    public function setSlider(Slider $slider)
    {
        $this->slider = $slider;
        return $this;
    }

    /**
     * @return SlideImage
     */
    public function getSlideImage()
    {
        return $this->slideImage;
    }

    /**
     * @param SlideImage $slideImage
     * @return Slide
     */
    public function setSlideImage($slideImage)
    {
        $this->slideImage = $slideImage;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return Slide
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
}
