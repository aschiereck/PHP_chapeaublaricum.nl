<?php

namespace App\Repository\Image;

use App\Model\Image\CarouselImage;

class CarouselImageRepository extends AbstractImageRepository implements ICarouselImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray)
    {
        $image = new CarouselImage();
        return $this->__fromUpload($image, $filesArray);
    }

    /**
     * @param CarouselImage $image
     */
    public function delete(CarouselImage $image)
    {
        $this->_em->remove($image);
        $this->_em->flush();
    }
}