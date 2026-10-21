<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="url", columns={"url"})}))
 */
class Site extends AbstractEntity
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
     * @var Page[]
     * @ORM\OneToMany(targetEntity="Page", mappedBy="site", cascade={"remove"})
     */
    private $pages;
    /**
     * @var Article[]
     * @ORM\OneToMany(targetEntity="Article", mappedBy="site", cascade={"remove"})
     */
    private $articles;
    /**
     * @var LayoutTextBlock[]
     * @ORM\OneToMany(targetEntity="LayoutTextBlock", mappedBy="site", cascade={"remove"})
     */
    private $layoutTextBlocks;
    /**
     * @var Slider[]
     * @ORM\OneToMany(targetEntity="Slider", mappedBy="site", cascade={"remove"})
     */
    private $sliders;
    /**
     * @var Form[]
     * @ORM\OneToMany(targetEntity="Form", mappedBy="site", cascade={"remove"})
     */
    private $forms;
    /**
     * @var MenuItem[]
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="site", cascade={"remove"})
     */
    private $menuItems;
    /**
     * @var PhotoAlbum[]
     * @ORM\OneToMany(targetEntity="PhotoAlbum", mappedBy="site", cascade={"remove"})
     */
    private $photoAlbums;
    /**
     * @var TeamMember[]
     * @ORM\OneToMany(targetEntity="TeamMember", mappedBy="site", cascade={"remove"})
     */
    private $teamMembers;
    /**
     * @var Project[]
     * @ORM\OneToMany(targetEntity="Project", mappedBy="site", cascade={"remove"})
     */
    private $projects;
    /**
     * @var Carousel[]
     * @ORM\OneToMany(targetEntity="Carousel", mappedBy="site", cascade={"remove"})
     */
    private $carousel;
    /**
     * @var Calendar[]
     * @ORM\OneToMany(targetEntity="Calendar", mappedBy="site", cascade={"remove"})
     */
    private $calendars;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $locale = Locale::NL;
    /**
     * @var SiteSetting[]
     * @ORM\OneToMany(targetEntity="SiteSetting", mappedBy="site", cascade={"remove"})
     */
    private $siteSettings;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->pages = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->layoutTextBlocks = new ArrayCollection();
        $this->sliders = new ArrayCollection();
        $this->forms = new ArrayCollection();
        $this->menuItems = new ArrayCollection();
        $this->photoAlbums = new ArrayCollection();
        $this->teamMembers = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->carousel= new ArrayCollection();
        $this->calendars = new ArrayCollection();
        $this->siteSettings = new ArrayCollection();
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
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @param string $name
     * @return Site
     */
    public function setName(string $name): Site
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return (string)$this->url;
    }

    /**
     * @param string $url
     * @return Site
     */
    public function setUrl(string $url): Site
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return Page[]
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @return Article[]
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @return LayoutTextBlock[]
     */
    public function getLayoutTextBlocks()
    {
        return $this->layoutTextBlocks;
    }

    /**
     * @return Slider[]
     */
    public function getSliders()
    {
        return $this->sliders;
    }

    /**
     * @return Form[]
     */
    public function getForms()
    {
        return $this->forms;
    }

    /**
     * @return MenuItem[]
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * @return PhotoAlbum[]
     */
    public function getPhotoAlbums()
    {
        return $this->photoAlbums;
    }

    /**
     * @return TeamMember[]
     */
    public function getTeamMembers()
    {
        return $this->teamMembers;
    }

    /**
     * @return Project[]
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @return Carousel[]
     */
    public function getCarousel()
    {
        return $this->carousel;
    }

    /**
     * @return Calendar[]
     */
    public function getCalendars()
    {
        return $this->calendars;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }
}
