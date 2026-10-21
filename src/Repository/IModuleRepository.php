<?php
namespace App\Repository;

interface IModuleRepository
{
    /**
     * @param string $moduleName
     * @return bool
     */
    public function isEnabled($moduleName);

    /**
     * @return string[]
     */
    public function getActiveModules();
}