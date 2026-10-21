<?php

namespace App\Model;

use App\Model\Image\CarouselImage;
use App\Model\Link\AbstractLink;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class CarouselItem extends AbstractEntity
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
     * @var Carousel
     * @ORM\ManyToOne(targetEntity="Carousel", inversedBy="items")
     */
    private $carousel;

    /**
     * @var CarouselImage
     * @ORM\OneToOne(targetEntity="\App\Model\Image\CarouselImage", orphanRemoval=true, cascade={"remove"})
     */
    private $carouselImage;


    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();

        $nameValidation = new ValidatorChain();
        $nameValidation
            ->attach(new NotEmpty());

        $imageValidation = new ValidatorChain();
        $imageValidation
            ->attach(new NotEmpty());

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }
        if(!$imageValidation->isValid($this->carouselImage))
        {
            $invalidFields['image'] = $imageValidation->getMessages();
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
     * @return CarouselItem
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return CarouselItem
     */
    public function setLink($link)
    {
        $this->link = $link;
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
     * @return CarouselItem
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return Carousel
     */
    public function getCarousel()
    {
        return $this->carousel;
    }

    /**
     * @param Carousel $carousel
     * @return CarouselItem
     */
    public function setCarousel($carousel)
    {
        $this->carousel = $carousel;
        return $this;
    }

    /**
     * @return CarouselImage
     */
    public function getCarouselImage()
    {
        return $this->carouselImage;
    }

    /**
     * @param CarouselImage $carouselImage
     * @return CarouselItem
     */
    public function setCarouselImage($carouselImage)
    {
        $this->carouselImage = $carouselImage;
        return $this;
    }
}
