<?php

namespace App\Model\Link;

use App\Model\Page;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class PageLink extends AbstractLink
{
    const TYPE = 'page';

    /**
     * @var Page
     * @ORM\ManyToOne(targetEntity="\App\Model\Page")
     */
    private $page = '';

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     * @return PageLink
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }
}

