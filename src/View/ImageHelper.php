<?php

namespace App\View;

use App\Action\Image\ResizerAction;
use App\Model\Image\AbstractImage;
use Zend\View\Helper\AbstractHelper;

class ImageHelper extends AbstractHelper
{
    /**
     * @param AbstractImage $image
     * @return string
     */
    public function getUrl($image)
    {
        if($image)
        {
            return MEDIA_FOLDER_URL . $image->getModelName() . '/' . $image->getFileName();
        }
        else
        {
            return '';
        }
    }

    /**
     * @param AbstractImage $image
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getResizeUrl($image, $width, $height)
    {
        return $this->createResizeUrl($image, $width, $height, ResizerAction::RESIZE_TYPE_BEST_FIT);
    }

    /**
     * @param AbstractImage $image
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getExactResizeUrl($image, $width, $height)
    {
        return $this->createResizeUrl($image, $width, $height, ResizerAction::RESIZE_TYPE_EXACT);
    }

    /**
     * @param AbstractImage $image
     * @param int $width
     * @param int $height
     * @param $type
     * @return string
     * @internal param bool $isThumbnail
     */
    private function createResizeUrl($image, $width, $height, $type)
    {
        if($image)
        {
            return MEDIA_CACHE_FOLRDER_URL . $image->getModelName() . '/' . $image->getId() . '/' . $type . '-' . $width . '-' . $height . '-' . $image->getFileName();
        }
        else
        {
            return '';
        }
    }
}