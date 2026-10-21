<?php

namespace App\Repository\Image;

use App\Model\Image\SlideImage;

interface ISlideImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray);

    /**
     * @param SlideImage $slideImage
     */
    public function delete(SlideImage $slideImage);
}