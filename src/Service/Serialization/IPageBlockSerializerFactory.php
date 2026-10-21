<?php
namespace App\Service\Serialization;

use App\Service\Serialization\PageBlock\IPageBlockSerializer;

interface IPageBlockSerializerFactory
{
    /**
     * @param string $pageBlockType
     * @return IPageBlockSerializer
     */
    public function getPageBlockSerializer($pageBlockType);
}