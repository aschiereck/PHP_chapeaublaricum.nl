<?php

namespace App\Repository\Image;

use App\Model\Image\SlideImage;

class SlideImageRepository extends AbstractImageRepository  implements ISlideImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray)
    {
        $slideImage = new SlideImage();
        return $this->__fromUpload($slideImage, $filesArray);
    }

    /**
     * @param SlideImage $slideImage
     */
    public function delete(SlideImage $slideImage)
    {
        $this->_em->remove($slideImage);
        $this->_em->flush();
    }
}