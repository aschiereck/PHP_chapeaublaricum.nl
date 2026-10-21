<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use App\Model\Image\PhotoImage;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class Photo extends AbstractEntity
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
     * @var PhotoAlbum
     * @ORM\ManyToOne(targetEntity="PhotoAlbum", inversedBy="photos")
     */
    private $photoAlbum;
    /**
     * @var PhotoImage
     * @ORM\OneToOne(targetEntity="\App\Model\Image\PhotoImage", orphanRemoval=true, cascade={"remove"})
     */
    private $photoImage;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $position;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;

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
     * @return Photo
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return PhotoAlbum
     */
    public function getPhotoAlbum()
    {
        return $this->photoAlbum;
    }

    /**
     * @param PhotoAlbum $photoAlbum
     * @return Photo
     */
    public function setPhotoAlbum($photoAlbum)
    {
        $this->photoAlbum = $photoAlbum;
        return $this;
    }

    /**
     * @return PhotoImage
     */
    public function getPhotoImage()
    {
        return $this->photoImage;
    }

    /**
     * @param PhotoImage $photoImage
     * @return Photo
     */
    public function setPhotoImage($photoImage)
    {
        $this->photoImage = $photoImage;
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
     * @return Photo
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     * @return Photo
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }
}