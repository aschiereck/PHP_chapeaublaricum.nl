<?php

namespace App\Model;

use App\Model\PageBlock\PageBlockPhotoAlbum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class PhotoAlbum extends AbstractEntity
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
     * @var Photo[]
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="photoAlbum", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $photos;

    /**
     * @var PageBlockPhotoAlbum[]
     * @ORM\OneToMany(targetEntity="\App\Model\PageBlock\PageBlockPhotoAlbum", mappedBy="photoAlbum", cascade={"remove"})
     */
    private $pageBlocks;
    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="photoAlbums")
     */
    private $site;

    /**
     * Slider constructor.
     */
    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->pageBlocks = new ArrayCollection();
    }

    /**
     * Validate the model
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

    /***************************************************************************************
     * Getters / Setters
     ***************************************************************************************/

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PhotoAlbum
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Photo[]
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @return PageBlock\PageBlockPhotoAlbum[]
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
     * @return PhotoAlbum
     */
    public function setSite(Site $site): PhotoAlbum
    {
        $this->site = $site;
        return $this;
    }
}