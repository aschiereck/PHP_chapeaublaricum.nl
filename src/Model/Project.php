<?php

namespace App\Model;

use App\Model\Image\ProjectImage;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\Uri;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="url_idx", columns={"site_id", "url"})}))
 */
class Project extends AbstractEntity
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
     * @var string
     * @ORM\Column(type="string")
     */
    private $url;
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $content = '';
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $summary = '';
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $headTitle = '';
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $metaDescription = '';
    /**
     * @var PhotoAlbum
     * @ORM\ManyToOne(targetEntity="\App\Model\PhotoAlbum", inversedBy="projects")
     * @ORM\JoinColumn(nullable=true,  onDelete="SET NULL")
     */
    private $photoAlbum;
    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $modifiedDate;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $position;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $focusWords = '';
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $seoCompleteness;
    /**
     * @var ProjectImage
     * @ORM\OneToOne(targetEntity="\App\Model\Image\ProjectImage", orphanRemoval=true, cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;
    /**
     * @var PageBlockProject[]
     * @ORM\OneToMany(targetEntity="\App\Model\PageBlockProject", mappedBy="project", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $pageBlockProjects;
    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="projects")
     */
    private $site;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->pageBlockProjects = new ArrayCollection();
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

        $urlValidation = new ValidatorChain();
        $urlValidation
            ->attach(new Uri(['allowRelative'=>true]));

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }
        if($this->url != '' && !$urlValidation->isValid($this->url))
        {
            $invalidFields['url'] = $urlValidation->getMessages();
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
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Project
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
     * @return Project
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * @return Project
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeadTitle()
    {
        return $this->headTitle;
    }

    /**
     * @param string $headTitle
     * @return Project
     */
    public function setHeadTitle($headTitle)
    {
        $this->headTitle = $headTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     * @return Project
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
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
     * @return Project
     */
    public function setPhotoAlbum($photoAlbum)
    {
        $this->photoAlbum = $photoAlbum;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * @param DateTime $modifiedDate
     * @return Project
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
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
     * @return Project
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return ProjectImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ProjectImage $image
     * @return Project
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeoCompleteness()
    {
        return $this->seoCompleteness;
    }

    /**
     * @param int $seoCompleteness
     * @return Project
     */
    public function setSeoCompleteness($seoCompleteness)
    {
        $this->seoCompleteness = $seoCompleteness;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getFocusWords()
    {
        return implode(', ', explode(',', $this->focusWords));
    }

    /**
     * @param string $focusWordsAsString
     * @return Project
     */
    public function setFocusWords($focusWordsAsString)
    {
        $focusWords = explode(',', $focusWordsAsString);
        $focusWords = array_map('trim', $focusWords);
        $focusWords = (string)implode(',', $focusWords);
        $this->focusWords = $focusWords;

        return $this;
    }

    /**
     * @return array
     */
    public function getFocusWordsList()
    {
        if(!$this->focusWords)
        {
            return [];
        }
        else
        {
            return explode(',', $this->focusWords);
        }
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
     * @return Project
     */
    public function setSite(Site $site): Project
    {
        $this->site = $site;
        return $this;
    }
}