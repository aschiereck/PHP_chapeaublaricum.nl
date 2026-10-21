<?php

namespace App\Model\PageBlock;

use App\Model\PhotoAlbum;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockPhotoAlbum extends AbstractPageBlock
{
    const TEMPLATE = 'photo-album';

    /**
     * @var PhotoAlbum
     * @ORM\ManyToOne(targetEntity="\App\Model\PhotoAlbum", inversedBy="pageBlocks")
     */
    private $photoAlbum;

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
     * @return PhotoAlbum
     */
    public function getPhotoAlbum()
    {
        return $this->photoAlbum;
    }

    /**
     * @param PhotoAlbum $photoAlbum
     * @return PageBlockPhotoAlbum
     */
    public function setPhotoAlbum($photoAlbum)
    {
        $this->photoAlbum = $photoAlbum;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditorTitle()
    {
        return $this->photoAlbum ? $this->photoAlbum->getName() : '';
    }
}