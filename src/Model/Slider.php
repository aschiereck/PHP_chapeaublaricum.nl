<?php

namespace App\Model;

use App\Model\PageBlock\PageBlockSlider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class Slider extends AbstractEntity
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
     * @var Slide[]
     * @ORM\OneToMany(targetEntity="Slide", mappedBy="slider", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $slides;

    /**
     * @var PageBlockSlider[]
     * @ORM\OneToMany(targetEntity="\App\Model\PageBlock\PageBlockSlider", mappedBy="slider", cascade={"remove"})
     */
    private $pageBlocks;

    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="sliders")
     */
    private $site;

    /**
     * Slider constructor.
     */
    public function __construct()
    {
        $this->slides = new ArrayCollection();
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
     * @param int $id
     * @return Slider
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return Slider
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Slide[]
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * @return PageBlockSlider[]
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
     * @return Slider
     */
    public function setSite(Site $site): Slider
    {
        $this->site = $site;
        return $this;
    }
}
