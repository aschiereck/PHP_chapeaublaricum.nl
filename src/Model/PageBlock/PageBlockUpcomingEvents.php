<?php

namespace App\Model\PageBlock;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockUpcomingEvents extends AbstractPageBlock
{
    const TEMPLATE = 'upcoming-events';

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $numberOfEvents = 2;

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
     * @return int
     */
    public function getNumberOfEvents() : int
    {
        return (int)$this->numberOfEvents;
    }

    /**
     * @param int $numberOfEvents
     * @return PageBlockUpcomingEvents
     */
    public function setNumberOfEvents($numberOfEvents)
    {
        $this->numberOfEvents = $numberOfEvents;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditorTitle()
    {
        return $this->getNumberOfEvents();
    }
}