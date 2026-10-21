<?php

namespace App\Repository;

use App\Model\PageBlockMetaInformation;
use App\Model\PageBlock\AbstractPageBlock;

interface IPageBlockMetaRepository
{
    /**
     * @return PageBlockMetaInformation[]
     */
    public function getAllPageBlockMetaInformation();

    /**
     * @param AbstractPageBlock $model
     * @return string
     */
    public function getModuleTagByModel($model);

    /**
     * @param AbstractPageBlock $model
     * @return bool
     */
    public function renderContainer($model);
}