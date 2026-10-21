<?php

namespace App\Model\PageBlock;

use App\Model\Link\AbstractLink;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockCustomTemplate extends AbstractPageBlock
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $text = '';

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $content = '';

    /**
     * @var \App\Model\Image\PageBlockImage
     * @ORM\OneToOne(targetEntity="\App\Model\Image\PageBlockImage", orphanRemoval=true, cascade={"remove"})
     */
    private $image;

    /**
     * @var AbstractLink
     * @ORM\OneToOne(targetEntity="\App\Model\Link\AbstractLink", orphanRemoval=true, cascade={"remove", "persist"})
     * @ORM\JoinColumn(nullable=true,  onDelete="SET NULL")
     */
    private $link;

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
        return '';
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return PageBlockCustomTemplate
     */
    public function setText($text)
    {
        $this->text = (string)$text;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return PageBlockCustomTemplate
     */
    public function setContent($content)
    {
        $this->content = (string)$content;
        return $this;
    }

    /**
     * @return \App\Model\Image\PageBlockImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param \App\Model\Image\PageBlockImage $image
     * @return PageBlockCustomTemplate
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * @return PageBlockCustomTemplate
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }
}