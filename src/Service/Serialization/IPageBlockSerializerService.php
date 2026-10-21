<?php
namespace App\Service\Serialization;

use App\Model\PageBlock\AbstractPageBlock;
use App\Model\PageBlock\PageBlockSlider;

interface IPageBlockSerializerService
{
    /**
     * @param AbstractPageBlock $pageBlock
     * @return array
     */
    public function toArray(AbstractPageBlock $pageBlock);

    /**
     * @param array
     * @return PageBlockSlider
     */
    public function fromArray($values);
}