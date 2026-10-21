<?php

namespace App\Model;

use App\Model\PageBlock\PageBlockCalendar;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class Calendar extends AbstractEntity
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
     * @var CalendarEvent[]
     * @ORM\OneToMany(targetEntity="CalendarEvent", mappedBy="calendar", cascade={"remove", "persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"dateTime" = "ASC"})
     */
    private $events;

    /**
     * @var PageBlockCalendar[]
     * @ORM\OneToMany(targetEntity="\App\Model\PageBlock\PageBlockCalendar", mappedBy="calendar", cascade={"remove"})
     */
    private $pageBlocks;
    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="calendars")
     */
    private $site;

    /**
     * Slider constructor.
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
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
     * @return Calendar
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return CalendarEvent[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param CalendarEvent[] $events
     * @return Calendar
     */
    public function setEvents($events)
    {
        foreach($this->events as $event)
        {
            $this->events->removeElement($event);
        }
        $this->events = $events;
        return $this;
    }

    /**
     * @return PageBlockCalendar[]
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
     * @return Calendar
     */
    public function setSite(Site $site): Calendar
    {
        $this->site = $site;
        return $this;
    }
}
