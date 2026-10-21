<?php

namespace App\Model;

use App\Model\PageBlock\PageBlockCarousel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class Carousel extends AbstractEntity
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
    private $name;

    /**
     * @var CarouselItem[]
     * @ORM\OneToMany(targetEntity="CarouselItem", mappedBy="carousel", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $items;

    /**
     * @var PageBlockCarousel[]
     * @ORM\OneToMany(targetEntity="\App\Model\PageBlock\PageBlockCarousel", mappedBy="carousel", cascade={"remove"})
     */
    private $pageBlocks;
    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="carousels")
     */
    private $site;

    /**
     * Slider constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();

        $nameValidation = new ValidatorChain();
        $nameValidation
            ->attach(new NotEmpty());

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
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
     * @return Carousel
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return CarouselItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param CarouselItem[] $items
     * @return Carousel
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return PageBlockCarousel[]
     */
    public function getPageBlocks()
    {
        return $this->pageBlocks;
    }

    /**
     * @return string
     */
    public function getUsedOnText()
    {
        $names = [];
        foreach($this->pageBlocks as $pageBlock)
        {
            $names[] = $pageBlock->getPage()->getName();
        }
        return implode(', ', $names);
    }

    /**
     * @return Site
     */
    public function getSite(): Site
    {
        return $this->site;
    }

    /**
     * @param Site $site
     * @return Carousel
     */
    public function setSite(Site $site): Carousel
    {
        $this->site = $site;
        return $this;
    }

}
