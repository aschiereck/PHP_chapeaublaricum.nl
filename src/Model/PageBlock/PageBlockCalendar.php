<?php

namespace App\Model\PageBlock;

use App\Model\Calendar;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockCalendar extends AbstractPageBlock
{
    const TEMPLATE = 'calendar';

    /**
     * @var Calendar
     * @ORM\ManyToOne(targetEntity="\App\Model\Calendar", inversedBy="pageBlocks")
     */
    private $calendar;

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
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     * @return PageBlockCalendar
     */
    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditorTitle()
    {
        return $this->calendar ? $this->calendar->getName() : '';
    }
}