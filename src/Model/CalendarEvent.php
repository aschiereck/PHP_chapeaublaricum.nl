<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class CalendarEvent extends AbstractEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @var Calendar
     * @ORM\ManyToOne(targetEntity="Calendar", inversedBy="events")
     */
    private $calendar;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $location;

    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();
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
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param DateTime $dateTime
     * @return CalendarEvent
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
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
     * @return CalendarEvent
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return CalendarEvent
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     * @return CalendarEvent
     */
    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;
        return $this;
    }
}
