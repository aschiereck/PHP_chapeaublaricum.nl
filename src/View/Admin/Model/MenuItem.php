<?php

namespace App\View\Admin\Model;

class MenuItem
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $label;
    /**
     * @var array
     */
    public $route;
    /**
     * @var bool
     */
    public $isActive = false;
    /**
     * @var int
     */
    public $count = 0;
    /**
     * @var string
     */
    public $module;

    /**
     * MenuItem constructor.
     * @param string $name
     * @param string $label
     * @param string $route
     * @param string $module
     */
    public function __construct($name, $label, $route, $module)
    {
        $this->name = $name;
        $this->label = $label;
        $this->route = $route;
        $this->module = $module;
    }
}