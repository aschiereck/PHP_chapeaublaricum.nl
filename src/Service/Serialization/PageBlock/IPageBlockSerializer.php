<?php
namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\AbstractPageBlock;

interface IPageBlockSerializer
{
    /**
     * @param AbstractPageBlock $pageBlock
     * @return string
     */
    public function toArray($pageBlock);

    /**
     * @param array $values
     * @param AbstractPageBlock $pageBlock
     * @return AbstractPageBlock $pageBlock
     */
    public function fromArray($values, $pageBlock);
}