<?php

namespace App\Repository;

class ModuleRepository implements IModuleRepository
{
    /**
     * @var string[]
     */
    private $activeModules = [];

    /**
     * @param string[] $activeModules
     */
    public function __construct($activeModules)
    {
        $this->activeModules = $activeModules;
    }

    /**
     * @param string $moduleName
     * @return bool
     */
    public function isEnabled($moduleName)
    {
        return in_array($moduleName, $this->activeModules);
    }

    /**
     * @return string[]
     */
    public function getActiveModules()
    {
        return $this->activeModules;
    }
}