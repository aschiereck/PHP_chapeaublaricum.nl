<?php

namespace App\View\Admin\Model;

class MenuGroup
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var MenuItem[]
     */
    public $menuItems = [];

    /**
     * MenuGroup constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function containsActiveChild()
    {
        foreach($this->menuItems as $item)
        {
            if($item->isActive)
            {
                return true;
            }
        }
        return false;
    }
}